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
        $distance = 25;
        $haversineSelect  = "*, (3959 * acos( cos( radians(" . $request->lat;
        $haversineSelect .= ") ) * cos( radians( lat ) ) * ";
        $haversineSelect .= "cos( radians( lon ) - radians(" . $request->lon;
        $haversineSelect .= ") ) + sin( radians(" . $request->lat . ") ) ";
        $haversineSelect .= "* sin( radians( lat ) ) ) ) AS distance";

        //if ($request->ajax()) {

            if (!$request->has('s_code')) {
                DB::setFetchMode(\PDO::FETCH_ASSOC);
                $physicians = DB::table('physicians')
                    ->select(DB::raw($haversineSelect))
                    ->where('last_name', 'like', $request->name . '%')
                    ->orWhere('first_name', 'like', $request->name . '%')
                    ->having('distance', '<', $distance)
                    ->orderBy('distance', 'asc')
                    ->get();
                DB::setFetchMode(\PDO::FETCH_CLASS);

            } else  {
                DB::setFetchMode(\PDO::FETCH_ASSOC);
                $physicians = DB::table('physicians')
                    ->select(DB::raw($haversineSelect))
                    ->where('PrimaryPracticeFocusCode', '=', $request->s_code )
                    ->having('distance', '<', $distance)
                    ->orderBy('distance', 'asc')
                    ->get();
                    DB::setFetchMode(\PDO::FETCH_CLASS);

            } 
            if (! empty($physicians)) {
                return $this->respond([
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
}
