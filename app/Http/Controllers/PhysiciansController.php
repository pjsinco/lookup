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

    /**
     * Default search distance in miles.
     *
     */
    private $defaultDistance = 25;
    private $maxDistance = 250;

    /**
     * Sequence of fallback distances to use in trying to return
     * a nonempty result set.
     */
    private $fallbackDistances = [25, 50, 100, 250];

    public function __construct(PhysicianTransformer $physicianTransformer)
    {
        $this->physicianTransformer = $physicianTransformer;
    }

    /**
     * Get the next distance to try.
     * Ex.: 33 is passed in, return 50.
     * Ex.: 100 is passed in, return 250.
     *
     * @param int
     * @return int
     */
    public function getNextDistance($queriedDistance = 0)
    {
        foreach ($this->fallbackDistances as $distance) {
            if ($distance > $queriedDistance) {
                return $distance;
            }
        }

        // the queriedDistance is already beyond our distances
        return $queriedDistance;
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
     * @param Request $request
     * @param Specialty $specialty
     * @param int $distance
     * @return Array
     */
    private function searchWithSpecialty(Request $request, 
        Specialty $specialty, $distance)
    {
        $searchDistance = $request->distance ? $request->distance : $distance;
        $haversineSelectStmt = $this->haversineSelect($request->lat, $request->lon);

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $physicians = DB::table('physicians')
            ->select(DB::raw($haversineSelectStmt))
            ->where('PrimaryPracticeFocusCode', '=', $specialty->code )
            ->having('distance', '<', $searchDistance)
            ->orderBy('distance', 'asc')
            ->get();
        DB::setFetchMode(\PDO::FETCH_CLASS);

        return $physicians;
    }

    /**
     * Search for physicians by first or last name.
     * 
     * @param Request $request
     * @param int $distance
     * @return Array
     */
    private function searchWithName(Request $request, $distance)
    {
        $searchDistance = $request->distance ? $request->distance : $distance;
        $haversineSelectStmt = $this->haversineSelect($request->lat, $request->lon);

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $physicians = DB::table('physicians')
            ->select(DB::raw($haversineSelectStmt))
            ->where('last_name', 'like', $request->q . '%' )
            ->orWhere('first_name', 'like', $request->q . '%' )
            ->having('distance', '<', $searchDistance)
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
        $searchDistance = $request->distance ? $request->distance : 0;

        if ($request->q != '') {
            $specialty = $this->getSpecialty($request->q);
        } else {
            $specialty = null;
        }

        $physicians = null;

        // if we don't have a requested distance, we'll cycle through
        // our fallback distances until we get at least 1 result;
        if (!$request->has('distance')) {

            while (empty($physicians)) {

                $searchDistance = $this->getNextDistance($searchDistance);

                if ($specialty) {
                    $physicians = $this->searchWithSpecialty(
                        $request, 
                        $specialty, 
                        $searchDistance
                    );
                } else if ($request->q != '') {
                    $physicians = $this->searchWithName(
                        $request, 
                        $searchDistance
                    );
                } else {
                    $haversineSelectStmt = 
                        $this->haversineSelect($request->lat, $request->lon);

                    DB::setFetchMode(\PDO::FETCH_ASSOC);
                    $physicians = DB::table('physicians')
                        ->select(DB::raw($haversineSelectStmt))
                        ->where('last_name', 'like', $request->name . '%')
                        ->orWhere('first_name', 'like', $request->name . '%')
                        ->having('distance', '<', $searchDistance)
                        ->orderBy('distance', 'asc')
                        ->get();
                    DB::setFetchMode(\PDO::FETCH_CLASS);
                }

                if ($searchDistance == $this->maxDistance) {
                    break;
                } 
            }
        } else {
            // We have a distance, so use it
            if ($specialty) {
                $physicians = $this->searchWithSpecialty(
                        $request, 
                        $specialty, 
                        $request->distance
                );
            } else if ($request->q != '') {
                $physicians = 
                    $this->searchWithName($request, $request->distance);
            } else {
                $haversineSelectStmt = 
                    $this->haversineSelect($request->lat, $request->lon);

                DB::setFetchMode(\PDO::FETCH_ASSOC);
                $physicians = DB::table('physicians')
                    ->select(DB::raw($haversineSelectStmt))
                    ->where('last_name', 'like', $request->name . '%')
                    ->orWhere('first_name', 'like', $request->name . '%')
                    ->having('distance', '<', $request->distance)
                    ->orderBy('distance', 'asc')
                    ->get();
                DB::setFetchMode(\PDO::FETCH_CLASS);
            }

        }

        //if ($request->ajax()) {

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
                        'count' => count($physicians),
                        'radius' => $searchDistance
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
                    'count' => count($physicians),
                    'radius' => $searchDistance
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
