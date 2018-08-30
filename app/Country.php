<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Country extends Model
{
    use Eloquence;


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
