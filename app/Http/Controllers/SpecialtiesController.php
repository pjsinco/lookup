<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
use Elit\Transofmers\SpecialtyTransformer;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpecialtiesController extends ApiController
{
    protected $specialtyTransformer;

    public function __construct(SpecialtyTransformer $specialtyTransformer)
    {
        $this->specialtyTransformer = $specialtyTransformer;
    }

    public function search(Request $request)
    {

    }
}
