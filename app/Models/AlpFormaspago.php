<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpFormaspago extends Model
{
    use SoftDeletes;

    public $table = 'alp_formas_pagos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_forma_pago',
        'descripcion_forma_pago',
        'orden',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_forma_pago' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_forma_pago' => 'required'
    ];
}
