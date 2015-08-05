<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;


    public static function random()
    {
        $location = \DB::table('locations')
            ->orderByRaw('RAND()')
            ->limit(1)
            ->get();
        return $location;
    }


}
