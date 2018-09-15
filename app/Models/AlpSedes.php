<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpSedes extends Model
{
    use SoftDeletes;

    public $table = 'alp_sedes';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_sede',
        'descripcion_sede',
        'latitud_sede',
        'longitud_sede',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_sede' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_sede' => 'required'
    ];
}
