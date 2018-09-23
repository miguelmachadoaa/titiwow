<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpClientes extends Model
{
    use SoftDeletes;

    public $table = 'alp_clientes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_user_client',
        'id_type_doc',
        'doc_cliente',
        'genero_cliente',
        'telefono_cliente',
        'marketing_cliente',
        'habeas_cliente',
        'estado_masterfile',
        'estado_registro',
        'id_empresa',
        'id_embajador',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];
}
