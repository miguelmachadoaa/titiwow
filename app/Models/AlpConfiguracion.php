<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpConfiguracion extends Model
{
    use SoftDeletes;

    public $table = 'alp_configuracion_general';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_tienda',
        'limite_amigos',
        'id_mercadopago',
        'key_mercadopago',
        'minimo_compra',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_tienda' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_tienda' => 'required'
    ];
}
