<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Location;
use App\Specialty;
use Response;
use Illuminate\Database\Eloquent\Collection;
use DB;
use App\Http\Requests;
use Elit\Transformers\PhysicianTransformer;
use App\Http\Controllers\Controller;

class PhysiciansController extends ApiController
{
    protected $physicianTransformer;

    public function __construct(PhysicianTransformer $physicianTransformer)
    {
        $this->physicianTransformer = $physicianTransformer;
    }

    /**
     * Return information for an individual physician.
     *
     * @param string
     * @return json
     * @param PJ
     */
    public function show($id)
    {
        \Debugbar::disable();

        $physician = \App\Physician::findOrFail($id);

        return $this->respond([
            'data' => $this->physicianTransformer->transform($physician)
        ]);
    }

    private function parseQField()
    {
        
    }

    private function isSpecialty()
    {

    }

    /**
     * Search for a specialty. Returns an array representation of the
     * Specialty model, or false if it's not found.
     *
     * @param string
     * @return instance of Specialty, or false
     */
    private function getSpecialty($query)
    {
        $query = trim(strtolower($query));
        $specialty = Specialty::where('full', 'like', $query)->get()->first();
        if (!empty($specialty)) {
            return $specialty;
        } 

        return false;
    }

    /**
     * Search for physicians who practice a certain specialty.
     * 
     * @param Request
     * @param Specialty
     * @return Array
     */
    private function searchWithSpecialty(Request $request, Specialty $specialty)
    {
        $distance = $request->distance ? $request->distance : 25;
        $haversineSelectStmt = $this->haversineSelect($request->lat, $request->lon);

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $physicians = DB::table('physicians')
            ->select(DB::raw($haversineSelectStmt))
            ->where('PrimaryPracticeFocusCode', '=', $specialty->code )
            ->having('distance', '<', $distance)
            ->orderBy('distance', 'asc')
            ->get();
        DB::setFetchMode(\PDO::FETCH_CLASS);

        return $physicians;
    }

    /**
     * Search for physicians by first or last name.
     * 
     * @param Request
     * @param Specialty
     * @return Array
     */
    private function searchWithName(Request $request)
    {
        $distance = $request->distance ? $request->distance : 25;
        $haversineSelectStmt = $this->haversineSelect($request->lat, $request->lon);

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $physicians = DB::table('physicians')
            ->select(DB::raw($haversineSelectStmt))
            ->where('last_name', 'like', $request->q . '%' )
            ->orWhere('first_name', 'like', $request->q . '%' )
            ->having('distance', '<', $distance)
            ->orderBy('distance', 'asc')
            ->get();
        DB::setFetchMode(\PDO::FETCH_CLASS);

        return $physicians;
    }

    /**
     * Search for a physician. 
     *
     * @param string
     * @return json
     * @author PJ
     */
    public function search(Request $request)
    {
        //\Debugbar::disable();

        if ($request->q != '') {
            $specialty = $this->getSpecialty($request->q);
        } else {
            $specialty = null;
        }


        if ($specialty) {
            $physicians = $this->searchWithSpecialty($request, $specialty);
        } else if ($request->q != '') {
            $physicians = $this->searchWithName($request);
        } else {
            $distance = $request->distance ? $request->distance : 25;
            $haversineSelectStmt = $this->haversineSelect($request->lat, $request->lon);

            DB::setFetchMode(\PDO::FETCH_ASSOC);
            $physicians = DB::table('physicians')
                ->select(DB::raw($haversineSelectStmt))
                ->where('last_name', 'like', $request->name . '%')
                ->orWhere('first_name', 'like', $request->name . '%')
                ->having('distance', '<', $distance)
                ->orderBy('distance', 'asc')
                ->get();
            DB::setFetchMode(\PDO::FETCH_CLASS);
        }
        //if ($request->ajax()) {

//            if (!$request->has('code')) {
//                DB::setFetchMode(\PDO::FETCH_ASSOC);
//                $physicians = DB::table('physicians')
//                    ->select(DB::raw($haversineSelectStmt))
//                    ->where('last_name', 'like', $request->name . '%')
//                    ->orWhere('first_name', 'like', $request->name . '%')
//                    ->having('distance', '<', $distance)
//                    ->orderBy('distance', 'asc')
//                    ->get();
//                DB::setFetchMode(\PDO::FETCH_CLASS);
//            } else  {
//                DB::setFetchMode(\PDO::FETCH_ASSOC);
//                $physicians = DB::table('physicians')
//                    ->select(DB::raw($haversineSelectStmt))
//                    ->where('PrimaryPracticeFocusCode', '=', $request->code )
//                    ->having('distance', '<', $distance)
//                    ->orderBy('distance', 'asc')
//                    ->get();
//                DB::setFetchMode(\PDO::FETCH_CLASS);
//            } 

            // TODO DRY up meta block

            if (! empty($physicians)) {
                return $this->respond([
                    'meta' => [
                        'city' => $request->city,
                        'state' => $request->state,
                        'zip' => $request->zip ? $request->zip : null,
                        'specialty' => 
                            !empty($specialty) ? $specialty->full : null,
                        'q' => $request->q,
                        //'code' => 
                            //$request->code ? $request->code : null,
                        'count' => count($physicians)
                    ],
                    'data' => $this->physicianTransformer
                                   ->transformCollection($physicians)
                ]);
            }

            $this->setStatusCode(404);
            return $this->respond([
                'meta' => [
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip ? $request->zip : null,
                    'specialty' => 
                        !empty($specialty) ? $specialty->full : null,
                    'q' => $request->q,
                    //'code' => 
                        //$request->code ? $request->code : null,
                    'count' => count($physicians)
                ],
                'error' => [
                    'message' => 'No physicians found',
                    'status_code' => 404,
                ]
            ]);
        //}
    }

    /**
     * Calcuate the number of physicians within a certain number of 
     * miles of a ZIP code.
     *
     * @param string
     * @return string
     */
    public function withinDistance(Request $request)
    {
        \Debugbar::disable();

        if (!$request->has(['miles', 'zip'])) {
            app()->abort(404);
        }

        $location = Location::where('zip', '=', $request->zip)
            ->get();
        $lat = $location[0]->lat;
        $lon = $location[0]->lon;
        $haversineSelectStmt = 
            $this->haversineSelect($lat, $lon);

        $physicians = DB::table('physicians')
            ->select(DB::raw($haversineSelectStmt))
            //->where('PrimaryPracticeFocusCode', '=', $request->s_code )
            ->having('distance', '<', $request->miles)
            ->orderBy('distance', 'asc')
            ->get();

        $count = (string)count($physicians);
        return json_encode(['count' => $count]);
    }
    
    /**
     * Generate the body of a SQL SELECT statement for
     * retrieving items within a radius if the given latitude, longitude
     * according to the Haversine formula.
     *
     * @param string 
     * @param string 
     * @return string
     */
    public function haversineSelect($lat, $lon) 
    {
        $haversineSelect  = "*, (3959 * acos( cos( radians(" . $lat;
        $haversineSelect .= ") ) * cos( radians( lat ) ) * ";
        $haversineSelect .= "cos( radians( lon ) - radians(" . $lon;
        $haversineSelect .= ") ) + sin( radians(" . $lat . ") ) ";
        $haversineSelect .= "* sin( radians( lat ) ) ) ) AS distance";

        return $haversineSelect;
    }

}
