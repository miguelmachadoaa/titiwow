<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpPagos extends Model
{
    use SoftDeletes;

    public $table = 'alp_ordenes_pagos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_orden',
        'id_forma_pago',
        'id_estatus_pago',
        'monto_pago',
        'json',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_orden' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_orden' => 'required'
    ];


    public function formapago()
    {
        return $this->hasMany('App\Models\AlpFormaspago', 'id_forma_pago', 'id');
    }
}
