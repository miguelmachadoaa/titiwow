<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class State extends Model
{
    use Eloquence;


    protected $table = 'config_states';
    protected $guarded  = ['id'];
    protected $searchableColumns = ['state_name'];

    public $fillable = [
        'id',
        'state_name',
        'country_id',
        'estado_registro',
        'id_user'
    ];
}
