<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
use Elit\Transformers\SpecialtyTransformer;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpecialtiesController extends ApiController
{
    protected $specialtyTransformer;

    public function __construct(SpecialtyTransformer $specialtyTransformer)
    {
        $this->specialtyTransformer = $specialtyTransformer;
    }

    public function index()
    {
        $specialties = \App\Specialty::all();
        return $this->respond([
            'data' =>
                $this->specialtyTransformer
                    ->transformCollection($specialties->all())
        ]);

    }
    
    public function search(Request $request)
    {

    }
}
