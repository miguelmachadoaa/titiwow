<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpCategorias extends Model
{
    use SoftDeletes;

    public $table = 'alp_categorias';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_categoria',
        'descripcion_categoria',
        'imagen_categoria',
        'id_categoria_parent',
        'slug',
        'seo_titulo',
        'seo_descripcion',
        'destacado',
        'order',
        'robots',
        'css_categoria',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_categoria' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_categoria' => 'required'
    ];
}
