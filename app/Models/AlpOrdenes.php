<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpOrdenes extends Model
{
    use SoftDeletes;

    public $table = 'alp_ordenes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'referencia',
        'id_cliente',
        'id_address',
        'id_forma_envio',
        'id_forma_pago',
        'monto_total',
        'cod_oracle_pedido',
        'ordencompra',
        'factura',
        'estatus',
        'estatus_pago',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'referencia' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'referencia' => 'required'
    ];
}
