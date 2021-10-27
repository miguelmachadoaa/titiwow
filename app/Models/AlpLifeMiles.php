<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpLifeMiles extends Model
{
    use SoftDeletes;

    public $table = 'alp_lifemiles';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_lifemile',
        'cantidad_millas',
        'minimo_compra',
        'fecha_inicio',
        'fecha_final',
        'id_almacen',
        'cantidad_cupones',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_lifemile' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_lifemile' => 'required'
    ];
}
