<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpEmpresasUser extends Model
{
    use SoftDeletes;

    public $table = 'alp_empresa_user';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_empresa',
        'id_cliente',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_empresa' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_empresa' => 'required'
    ];
}