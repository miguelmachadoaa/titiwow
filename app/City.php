<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class City extends Model
{
    use Eloquence;


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

