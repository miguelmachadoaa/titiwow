<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAnchetasCategorias extends Model
{
    use SoftDeletes;

    public $table = 'alp_ancheta_categorias';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_ancheta',
        'nombre_categoria',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo_cupon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo_cupon' => 'required'
    ];
}
