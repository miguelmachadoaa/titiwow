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
        'base_url',
        'limite_amigos',
        'id_mercadopago',
        'key_mercadopago',
        'public_key_mercadopago',
        'public_key_mercadopago_test',
        'mercadopago_sand',
        'minimo_compra',
        'comision_mp',
        'retencion_fuente_mp',
        'retencion_iva_mp',
        'retencion_ica_mp',
        'minimo_compra',
        'minimo_compra',
        'minimo_compra',
        'registro_publico',
        'correo_admin',
        'correo_shopmanager',
        'correo_shopmanagercorp',
        'correo_masterfile',
        'correo_sac',
        'correo_cedi',
        'correo_logistica',
        'correo_finanzas',
        'seo_title',
        'seo_type',
        'seo_url',
        'seo_image',
        'seo_site_name',
        'seo_description',
        'vence_ordenes',
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
