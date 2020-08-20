<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpCuponesAlmacen extends Model
{
    use SoftDeletes;

    public $table = 'alp_cupones_almacen';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_cupon',
        'id_almacen',
        'condicion',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_cupon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_cupon' => 'required'
    ];
}
