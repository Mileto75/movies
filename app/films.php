<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class films extends Model
{
    //
    protected $table = 'tbl_films';
    protected $primaryKey = 'film_id';
    public $timestamps = false;
}
