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
     * Search for a physician by last name.
     *
     * @param string
     * @return json
     * @author PJ
     */
    public function search(Request $request)
    {
dd($request->input());
        // for my own sake
        $q = "
            SELECT
            FROM physicians
            WHERE last_name LIKE" . $name . "%
                or WHERE first_name LIKE" . $name . "%
        ";

        $physicians = \App\Physician::where('last_name', 'like', $name . '%')
            ->orWhere('first_name', 'like', $name . '%')
            ->get();

        if ($physicians) {
            //return Response::json($physicians, 200);
            return $this->respond([
                'data' => 
                    $this->physicianTransformer
                        ->transformCollection($physicians->all())
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
