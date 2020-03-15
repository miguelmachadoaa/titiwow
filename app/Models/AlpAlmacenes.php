<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAlmacenes extends Model
{
    use SoftDeletes;

    public $table = 'alp_almacenes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_almacen',
        'descripcion_almacen',
        'id_city',
        'defecto',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_almacen' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_almacen' => 'required'
    ];
}
