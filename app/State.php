<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

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
