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
        'monto_total',
        'monto_total_base',
        'monto_descuento',
        'base_impuesto',
        'valor_impuesto',
        'monto_impuesto',
        'comision_mp',
        'retencion_fuente_mp',
        'retencion_iva_mp',
        'retencion_ica_mp',
        'cod_oracle_pedido',
        'ordencompra',
        'id_almacen',
        'tracking',
        'factura',
        'ip',
        'json',
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
