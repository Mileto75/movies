<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regisseur extends Model
{
    //
    protected $table = 'tbl_regisseurs';
    protected $primaryKey = 'regisseur_id';
    public $timestamps = false;
}
