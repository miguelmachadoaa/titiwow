<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpEmpresas extends Model
{
    use SoftDeletes;

    public $table = 'alp_empresas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_empresa',
        'descripcion_empresa',
        'descuento_empresa',
        'dominio',
        'imagen',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_empresa' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_empresa' => 'required'
    ];
}
