<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAmigos extends Model
{
    use SoftDeletes;

    public $table = 'alp_amigos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_cliente',
        'nombre_amigo',
        'apellido_amigo',
        'email_amigo',
        'token',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_cliente' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_cliente' => 'required'
    ];
}
