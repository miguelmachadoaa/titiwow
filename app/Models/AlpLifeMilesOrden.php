<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpLifeMilesOrden extends Model
{
    use SoftDeletes;

    public $table = 'alp_lifemiles_orden';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_lifemile',
        'id_codigo',
        'id_orden',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_lifemile' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_lifemile' => 'required'
    ];
}
