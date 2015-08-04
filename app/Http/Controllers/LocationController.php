<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Location;
use App;
use Response;

class LocationController extends Controller
{
    
    /**
     * Return a random location
     *
     * @return App\Location
     * @author PJ
     */
    public function random(Request $request)
    {
        if ($request->ajax()) {
            return Response::json(Location::random(), 200);
        }

        App::abort(404, 'Not authorized to view this page');
    }

}
