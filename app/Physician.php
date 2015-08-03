<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Physician extends Model
{
    public $timestamps = false;

    public $hidden = [
        'id',
        'aoa_mem_id',
    ];
}


