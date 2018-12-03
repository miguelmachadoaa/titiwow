<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlpAlpinistas extends Model
{
    use SoftDeletes;

    public $table = 'alp_cod_alpinistas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'documento_alpi',
        'codigo_alpi',
        'cod_oracle_cliente',
        'estatus_alpinista',
        'id_usuario_creado',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_alpi' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'documento_alpi' => 'required',
        'codigo_alpi' => 'required'
    ];
}
