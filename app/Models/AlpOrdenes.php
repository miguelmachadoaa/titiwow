<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Notifications\Notifiable;


class AlpOrdenes extends Model
{
    use SoftDeletes;
    use Notifiable;


    public $table = 'alp_ordenes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'referencia',
        'referencia_mp',
        'id_cliente',
        'id_address',
        'id_forma_envio',
        'id_forma_pago',
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
        'send_json_masc',
        'json_icg',
        'estado_compramas',
        'envio_compramas',
        'notas',
        'lifemiles_id', 
        'estatus',
        'estatus_pago',
        'id_caja',
        'tasa_dolar',
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

    
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

     public function detalles()
    {
        return $this->hasMany('App\Models\AlpDetalles', 'id_orden', 'id');
    }

     public function pagos()
    {

        return $this->hasMany('App\Models\AlpPagos', 'id_orden', 'id');

    }

    public function transacciones()
    {

        return $this->hasMany('App\Models\AlpTransacciones', 'id_orden', 'id');

    }

     public function cliente()
    {
        return $this->hasOne('App\User', 'id', 'id_cliente');
    }

     public function cajero()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }

     public function estado()
    {
        return $this->hasOne('App\Models\AlpEstatusOrdenes', 'id', 'estatus');
    }





}
