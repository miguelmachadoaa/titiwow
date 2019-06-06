<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpCupones extends Model
{
    use SoftDeletes;

    public $table = 'alp_cupones';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'codigo_cupon',
        'valor_cupon',
        'tipo_reduccion',
        'limite_uso',
        'limite_uso_persona',
        'fecha_inicio',
        'fecha_final',
        'monto_minimo',
        'maximo_productos',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo_cupon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo_cupon' => 'required'
    ];
}
