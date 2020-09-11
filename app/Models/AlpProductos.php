<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpProductos extends Model
{
    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

     /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'alp_productos.nombre_producto' => 10,
            'alp_productos.referencia_producto' => 5,
            'alp_productos.presentacion_producto' => 4,
            'alp_productos.descripcion_corta' => 4,
            'alp_productos.descripcion_larga' => 4,
            'alp_productos.referencia_producto_sap' => 4,
           // 'alp_productos.descripcion_corta' => 3,
        ]
    ];

    public $table = 'alp_productos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_producto',
        'tipo_producto',
        'presentacion_producto',
        'referencia_producto',
        'referencia_producto_sap',
        'descripcion_corta',
        'descripcion_larga',
        'imagen_producto',
        'imagen_tiny',
        'seo_titulo',
        'seo_descripcion',
        'enlace_youtube',
        'slug',
        'id_categoria_default',
        'id_impuesto',
        'id_marca',
        'precio_base',
        'pum',
        'medida',
        'destacado',
        'sugerencia',
        'orden',
        'robots',
        'mostrar_descuento',
        'cantidad',
        'unidad',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_producto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_producto' => 'required'
    ];

}
