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
         * &what=
         * &location=Freehold%2C+NY
         *
         * ?city=Washington
         * &state=DC
         * &zip=20221
         * &lat=38.894103
         * &lon=-77.028884
         * &what=Pediatrics
         * &location=Washington%2C+DC+20221
         *
         */
        $distance = 25;
        $haversineSelect  = "*, (3959 * acos( cos( radians(" . $request->lat;
        $haversineSelect .= ") ) * cos( radians( lat ) ) * ";
        $haversineSelect .= "cos( radians( lon ) - radians(" . $request->lon;
        $haversineSelect .= ") ) + sin( radians(" . $request->lat . ") ) ";
        $haversineSelect .= "* sin( radians( lat ) ) ) ) AS distance";

        //if ($request->ajax()) {

            if ($request->has('lat') && $request->has('lon') && $request->has('name')) {

                DB::setFetchMode(\PDO::FETCH_ASSOC);
                $physicians = DB::table('physicians')
                    ->select(DB::raw($haversineSelect))
                    ->where('last_name', 'like', $request->name . '%')
                    ->orWhere('first_name', 'like', $request->name . '%')
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
        //}

        /**
         * "city" => "Long Pine"
         * "state" => "NE"
         * "zip" => "69217"
         * "lat" => "42.436196"
         * "lon" => "-99.750801"
         * "s_code" => "OR"
         * "specialty" => "Orthopedics"
         * "location" => "Long Pine, NE 69217"
         * 
         * "city" => "Nakina"
         * "state" => "NC"
         * "zip" => "28455"
         * "lat" => "34.112441"
         * "lon" => "-78.653824"
         * "s_code" => ""
         * "specialty" => ""
         * "location" => "Nakina, NC 28455"
         */

        //DB::setFetchMode(\PDO::FETCH_ASSOC);
        if ($request->has('s_code')) {
            $physicians = DB::table('physicians')
                ->select(DB::raw($haversineSelect))
                ->where('PrimaryPracticeFocusCode', '=', $request->s_code )
                ->having('distance', '<', $distance)
                ->orderBy('distance', 'asc')
                ->get();
        } else {
            $physicians = DB::table('physicians')
                ->select(DB::raw($haversineSelect))
                ->having('distance', '<', $distance)
                ->orderBy('distance', 'asc')
                ->get();
        }
        //DB::setFetchMode(\PDO::FETCH_CLASS);

        return view('search.results')
            ->with('physicians', $physicians)
            ->with('city', $request->city)
            ->with('state', $request->state)
            ->with('specialty', $request->specialty);
        
        
    }
}
