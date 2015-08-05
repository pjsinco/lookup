<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Physician;

class ResultsController extends Controller
{
    

    /**
     * Show a list of results.
     *
     * @return void
     * @author PJ
     */
    public function index(Request $request)
    {
        $input = $request->input();
        $results = Physician::where('City', '=', $input['city'])
            ->where('State_Province', '=', $input['state'])
            ->get();

dd($results->all());
    }
}
