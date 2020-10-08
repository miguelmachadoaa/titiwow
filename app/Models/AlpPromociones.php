<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpPromociones extends Model
{
    use SoftDeletes;

    public $table = 'alp_promociones';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'tipo',
        'referencia',
        'fecha_inicio',
        'fecha_final',
        'monto_minimo',
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
