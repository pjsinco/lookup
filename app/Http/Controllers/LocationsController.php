<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use DB;
use Response;
use Input;
use Elit\Transformers\LocationTransformer;

class LocationsController extends ApiController
{

    protected $locationTransformer;
    
    public function __construct(LocationTransformer $locationTransformer)
    {
        $this->locationTransformer = $locationTransformer;
    }

    /**
     * Split string on comma.
     *
     * @param string
     * @return array
     * @author PJ
     */
    private function splitLocation($location)
    {
        return explode(',', $location);
    }

    /**
     * Determine whether a string has a comma.
     *
     * @param string
     * @return boolean
     * @author PJ
     */
    private function hasComma($string)
    {
        return mb_strpos($string, ',') > 0;
    }

    /**
     * Get locations based on Zip code.
     *
     * @param string
     * @return json
     */
    public function searchByZip(Request $request)
    {
        $locations = App\Location::where('zip', '=', $request->q)
            ->get();

        if (! $locations->isEmpty()) {
            return $this->respond([
                'data' =>
                    $this->locationTransformer->transformCollection($locations->all())
            ]);
        }

        return Response::json([
            'error' => [
                'message' => 'Location not found',
                'status_code' => 404,
            ]
        ], 404);

    }

    public function search(Request $request)
    {
        $location = $request->q;

        // if the query is on a zip code, search zip codes
//        if (is_numeric($location)) {
//            $locations = App\Location::where('zip', 'like', $location . '%')
//                ->get()
//        } 

        // if we have a comma, split the string on it
        if ($this->hasComma($location)) {
            $locationSplit = $this->splitLocation($location);
            $city = trim($locationSplit[0]);
            $state = trim($locationSplit[1]);

            $locations = App\Location::where('city', '=', $city)
                ->where('state', 'like', $state. '%')
                ->get();
        } else {
            $locations = App\Location::where('zip', 'like', $location . '%')
                ->orWhere('city', 'like', $location . '%')
                ->groupBy(['city', 'zip'])
                ->get();
        }
        
        if (! $locations->isEmpty()) {
            return $this->respond([
                'data' =>
                    $this->locationTransformer->transformCollection($locations->all())
            ]);
        }

        return Response::json([
            'error' => [
                'message' => 'Location not found',
                'status_code' => 404,
            ]
        ], 404);
    }

    public function index()
    {
        $locations = \App\Location::all();

        return $this->respond([
            'data' => $this->locationTransformer->TransformCollection($locations->all()),
        ]);
    }

    /**
     * Return a random location
     *
     * @return App\Location
     * @author PJ
     */
    public function random(Request $request)
    {
        if ($request->ajax()) {
            $location = (array) App\Location::random();
            if ($location) {
                return $this->respond([
                    'data' => $this->locationTransformer->transform($location),
                ]);
            }
        }

        App::abort(404, 'Not authorized to view this page');
    }

    /**
     * Search for a location by city or zip
     *
     * @param string
     * @return json
     * @author PJ
     */
    public function show($location)
    {
        /**
         * EXAMPLE 
         * 
         * select *
         * from `locations`
         * where `zip` like 'naper%' or `city` like 'naper%'
         * group by city, zip
         */

        $locations = App\Location::where('zip', 'like', $location . '%')
            ->orWhere('city', 'like', $location . '%')
            ->groupBy(['city', 'zip'])
            ->get();
        
        if (! $locations->isEmpty()) {
            return Response::json($locations, 200);
        }

        return Response::json([
            'error' => [
                'message' => 'Location not found',
                'status_code' => 404,
            ]
        ], 404);
    }

    public function testSearch(Request $request) 
    {
        
    }

    public function withinDistance(Request $request)
    {
        $q =
        "SELECT 
            city, 
            state, 
            (3959 * acos( cos( radians(" . $request->lat  . ") ) * cos( radians( lat ) ) * cos( radians( lon ) - radians(" . $request->lon . ") ) + sin( radians(" . $request->lat . ") ) * sin( radians( lat ) ) ) ) AS distance
        from locations
            having distance < $request->distance
        order by distance ASC";
        
        $locations = DB::select($q);
    }

}
