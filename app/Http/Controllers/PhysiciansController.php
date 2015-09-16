<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Location;
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

    /**
     * Search for a physician. 
     *
     * @param string
     * @return json
     * @author PJ
     */
    public function search(Request $request)
    {
        \Debugbar::disable();
        $distance = 25;

        $haversineSelectStmt = $this->haversineSelect($request->lat, $request->lon);
        //if ($request->ajax()) {

            if (!$request->has('s_code')) {
                DB::setFetchMode(\PDO::FETCH_ASSOC);
                $physicians = DB::table('physicians')
                    ->select(DB::raw($haversineSelectStmt))
                    ->where('last_name', 'like', $request->name . '%')
                    ->orWhere('first_name', 'like', $request->name . '%')
                    ->having('distance', '<', $distance)
                    ->orderBy('distance', 'asc')
                    ->get();
                DB::setFetchMode(\PDO::FETCH_CLASS);

            } else  {
                DB::setFetchMode(\PDO::FETCH_ASSOC);
                $physicians = DB::table('physicians')
                    ->select(DB::raw($haversineSelectStmt))
                    ->where('PrimaryPracticeFocusCode', '=', $request->s_code )
                    ->having('distance', '<', $distance)
                    ->orderBy('distance', 'asc')
                    ->get();
                DB::setFetchMode(\PDO::FETCH_CLASS);

            } 
            if (! empty($physicians)) {
                return $this->respond([
                    'meta' => [
                        'city' => $request->city,
                        'state' => $request->state,
                        'zip' => $request->zip,
                        'specialty' => $request->specialty
                    ],
                    'data' => $this->physicianTransformer
                                   ->transformCollection($physicians)
                ]);
            }

            return Response::json([
                'error' => [
                    'message' => 'No physicians found',
                    'status_code' => 404,
                ]
            ], 404);
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
    public function haversineSelect($lat, $lon) {

        $haversineSelect  = "*, (3959 * acos( cos( radians(" . $lat;
        $haversineSelect .= ") ) * cos( radians( lat ) ) * ";
        $haversineSelect .= "cos( radians( lon ) - radians(" . $lon;
        $haversineSelect .= ") ) + sin( radians(" . $lat . ") ) ";
        $haversineSelect .= "* sin( radians( lat ) ) ) ) AS distance";

        return $haversineSelect;
    }
}
