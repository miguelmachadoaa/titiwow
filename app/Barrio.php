<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{

    protected $table = 'config_barrios';
    protected $guarded  = ['id'];
    protected $searchableColumns = ['city_name'];

    public $fillable = [
        'id',
        'barrio_name',
        'city_id',
        'estado_registro',
        'id_user'
    ];
}

