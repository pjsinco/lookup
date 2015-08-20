<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        /**
         * Example queries
         *   
         * ?city=Grand+Rapids
         * &state=MI
         * &zip=
         * &lat=42.961458
         * &lon=-85.661874
         * &what=
         * &location=Grand+Rapids%2C+MI
         * 
         * ?city=Buckingham
         * &state=IL
         * &zip=60917
         * &lat=41.044008
         * &lon=-88.170607
         * &what=
         * &location=Buckingham%2C+IL+60917
         * 
         * ?city=Batavia
         * &state=IL
         * &zip=
         * &lat=41.845761
         * &lon=-88.31066
         * &what=
         * &location=Batavia%2C+IL
         * 
         * ?city=Naperville
         * &state=IL
         * &zip=60563
         * &lat=41.792577
         * &lon=-88.165647
         * &what=
         * &location=Naperville%2C+IL+60563
         * 
         * ?city=Grand+Rapids
         * &state=MI
         * &zip=
         * &lat=42.961458
         * &lon=-85.661874
         * &what=Pediatrics
         * &location=Grand+Rapids%2C+MI
         * 
         * ?city=Freehold
         * &state=NY
         * &zip=
         * &lat=42.377411
         * &lon=-74.056683
         * &what=Robert+N.+Pedowitz%2C+DO
         * &location=Freehold%2C+NY
         *
         */

        if ($request->ajax()) {
            $name = $request->name;
            $lat = $request->lat;
            $lon = $request->lon;

        }


        if ($request->has('lat') && $request->has('lon') && $request->has('name')) {

            $distance = 25;
            $selectStatement = "*, (3959 * acos( cos( radians(" . $lat  . ") ) * cos( radians( lat ) ) * cos( radians( lon ) - radians(" . $lon . ") ) + sin( radians(" . $lat . ") ) * sin( radians( lat ) ) ) ) AS distance";

            $q = "
                SELECT 
                    *, 
                    (3959 * acos( cos( radians(" . $lat  . ") ) * cos( radians( lat ) ) * cos( radians( lon ) - radians(" . $lon . ") ) + sin( radians(" . $lat . ") ) * sin( radians( lat ) ) ) ) AS distance
                FROM locations
                WHERE `last_name` like '$name'
                    or `first_name` like '$name'
                HAVING distance < $distance
                ORDER BY distance ASC
            ";

            DB::setFetchMode(\PDO::FETCH_ASSOC);
            $physicians = DB::table('physicians')
                ->select(DB::raw($selectStatement))
                ->where('last_name', 'like', $name . '%')
                ->orWhere('first_name', 'like', $name . '%')
                ->having('distance', '<', $distance)
                ->orderBy('distance', 'asc')
                ->get();
            DB::setFetchMode(\PDO::FETCH_CLASS);

            if (! empty($physicians)) {
                return $this->respond([
                    'data' => 
                        $this->physicianTransformer->transformCollection($physicians)
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
}
