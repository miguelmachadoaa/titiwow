<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpCarritoDetalle extends Model
{
    use SoftDeletes;

    public $table = 'alp_carrito_detalle';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_carrito',
        'id_producto',
        'cantidad',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_carrito' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_carrito' => 'required'
    ];
}
