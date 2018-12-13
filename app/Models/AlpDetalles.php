<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpDetalles extends Model
{
    use SoftDeletes;

    public $table = 'alp_ordenes_detalle';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_orden',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'precio_total',
        'precio_base',
        'precio_total_base',
        'valor_impuesto',
        'monto_impuesto',
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
}
