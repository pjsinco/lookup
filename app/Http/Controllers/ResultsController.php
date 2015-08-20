<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Physician;

class ResultsController extends Controller
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

    public function search(Request $request)
    {

        $distance = 25;
// unfinished
    }
}
