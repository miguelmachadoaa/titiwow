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
        'tasa_dolar',
        'nombre_tienda',
        'base_url',
        'limite_amigos',
        'explicacion_precios',
        'mostrar_agotados',
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
        'correo_ultimamilla',
        'nombre_correo_respuesta',
        'seo_title',
        'seo_type',
        'seo_url',
        'seo_image',
        'seo_site_name',
        'seo_description',
        'vence_ordenes',
        'vence_ordenes_pago',
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
        'username_icg',
        'password_icg',
        'endpoint_icg',
        'porcentaje_icg',
        'token_icg',
        'dias_abono',
        'public_key_360',
        'private_key_360',
        'public_key_ws',
        'private_key_ws',
        'id_user',
        'popup',
        'popup_titulo',
        'popup_mensaje'
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
