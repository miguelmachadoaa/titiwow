<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpClientesHistory extends Model
{
    use SoftDeletes;

    public $table = 'alp_clientes_history';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_cliente',
        'estatus_cliente',        
        'notas',        
        'estatus',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_envio' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_envio' => 'required'
    ];
}
