<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpPrecioGrupo extends Model
{
    use SoftDeletes;

    public $table = 'alp_precios_grupos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_producto',
        'id_role',
        'city_id',
        'operacion',
        'precio',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_producto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_producto' => 'required'
    ];
}
