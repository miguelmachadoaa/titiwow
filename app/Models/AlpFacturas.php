<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlpFacturas extends Model
{
    use SoftDeletes;

    public $table = 'alp_cod_facturas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'orden_compra',
        'factura',
        'estatus_factura',
        'id_orden',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'orden_compra' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'orden_compra' => 'required',
        'factura' => 'required'
    ];
}
