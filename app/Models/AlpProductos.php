<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpProductos extends Model
{
    use SoftDeletes;

    public $table = 'alp_productos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_producto',
        'referencia_producto',
        'referencia_producto_sap',
        'descripcion_corta',
        'descripcion_larga',
        'imagen_producto',
        'seo_titulo',
        'seo_descripcion',
        'slug',
        'id_categoria_default',
        'id_marca',
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
