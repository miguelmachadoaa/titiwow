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
        'explicacion_precios',
        'id_mercadopago',
        'key_mercadopago',
        'public_key_mercadopago',
        'public_key_mercadopago_test',
        'mercadopago_sand',
        'minimo_compra',
        'maximo_productos',
        'comision_mp',
        'comision_mp_baloto',
        'comision_mp_efecty',
        'comision_mp_pse',
        'retencion_fuente_mp',
        'cuenta_twitter',
        'retencion_iva_mp',
        'retencion_ica_mp',
        'mensaje_bienvenida',
        'mensaje_promocion',
        'registro_publico',
        'user_activacion',
        'editar_direccion',
        'correo_admin',
        'correo_shopmanager',
        'correo_shopmanagercorp',
        'correo_masterfile',
        'correo_sac',
        'correo_cedi',
        'correo_logistica',
        'correo_finanzas',
        'correo_respuesta',
        'nombre_correo_respuesta',
        'seo_title',
        'seo_type',
        'seo_url',
        'seo_image',
        'seo_site_name',
        'seo_description',
        'vence_ordenes',
        'compramas_hash',
        'compramas_token',
        'compramas_url',
        'robots',
        'h1_home',
        'h1_marcas',
        'h1_categorias',
        'h1_terminos',
        'estado_registro',
        'token_api',
        'username_ibm',
        'password_ibm',
        'endpoint_ibm',
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
