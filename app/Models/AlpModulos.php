<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlpModulos extends Model
{
    use SoftDeletes;

    public $table = 'alp_modulos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_modulo',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_modulo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_modulo' => 'required'
    ];
}
