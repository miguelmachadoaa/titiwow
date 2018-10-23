<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $table = 'config_cities';
    protected $guarded  = ['id'];
    protected $searchableColumns = ['city_name'];

    public $fillable = [
        'id',
        'city_name',
        'state_id',
        'estado_registro',
        'id_user'
    ];
}

