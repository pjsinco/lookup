<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
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
        return \App\Physician::findOrFail($id);
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
        //$input = $request->input();
        $name = $request->name;
        $city = $request->city;

        if ($request->has('city')) {

            $physicians = \App\Physician::where(function($query) use ($name) {

                $query
                    ->where('last_name', 'like', $name . '%')
                    ->orWhere('first_name', 'like', $name . '%');

            })
                ->where('city', '=', $city)
                ->get();
        }

        if (isset($physicians) && $physicians != '') {
            //return Response::json($physicians, 200);
            return $this->respond([
                'data' => 
                    $this->physicianTransformer->transformCollection($physicians->all())
            ]);
        }

        return Response::json([
            'error' => [
                'message' => 'No physicians found',
                'status_code' => 404,
            ]
        ], 404);
        
    }
}
