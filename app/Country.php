<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'config_countries';
    protected $guarded  = ['id'];
    protected $searchableColumns = ['country_name'];

    public $fillable = [
        'id',
        'sortname',
        'country_name',
        'estado_registro',
        'id_user'
    ];
}
