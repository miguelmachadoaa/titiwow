<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpPuntos extends Model
{
    use SoftDeletes;

    public $table = 'alp_puntos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_orden',
        'id_cliente',
        'tipo',
        'cantidad',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_cliente' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_cliente' => 'required'
    ];
}
