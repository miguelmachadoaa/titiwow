<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpEstatusPagos extends Model
{
    use SoftDeletes;

    public $table = 'alp_pagos_estatus';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'estatus_pago_nombre',
        'estatus_pago_descripcion',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'estatus_pago_nombre' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'estatus_pago_nombre' => 'required'
    ];
}
