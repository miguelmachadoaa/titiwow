<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAlmacenDespacho extends Model
{
    use SoftDeletes;

    public $table = 'alp_almacen_despacho';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_almacen',
        'id_state',
        'id_city',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_almacen' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_almacen' => 'required'
    ];
}
