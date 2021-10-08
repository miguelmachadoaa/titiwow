<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpLifeMilesCodigos extends Model
{
    use SoftDeletes;

    public $table = 'alp_lifemiles_codigos';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_lifemile',
        'parnert',
        'miles',
        'fecha_inicio',
        'fecha_final',
        'estatus',
        'code',
        'prueba',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'parnert' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'parnert' => 'required'
    ];
}
