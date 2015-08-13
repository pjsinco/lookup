<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
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
        
        if ($locations) {
            return Response::json($locations, 200);
        }

        return Response::json([
            'error' => [
                'message' => 'Location not found',
                'status_code' => 404,
            ]
        ], 404);
    }

}
