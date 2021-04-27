<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpOrdenes extends Model
{
    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'alp_ordenes.id' => 10,
            'alp_ordenes.referencia' => 5,
            'alp_ordenes.origen' => 4,
            'users.first_name' => 4,
            'users.last_name' => 4,
            'users.email' => 4,
            'alp_clientes.telefono_cliente' => 4,
            'alp_almacenes.nombre_almacen' => 4,

           // 'alp_productos.descripcion_corta' => 3,
        ],
        'joins' => [
            'users' => ['alp_ordenes.id_cliente', 'users.id'],
            'alp_clientes' => ['alp_ordenes.id_cliente','alp_clientes.id_user_client'],
            'alp_almacenes' => ['alp_ordenes.id_almacen','alp_almacenes.id']

        ]
    ];



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
        'origen',
        'token',
        'tracking',
        'factura',
        'ip',
        'json',
        'json_icg',
        'estado_compramas',
        'envio_compramas',
        'notas',
        'countvp',  
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
