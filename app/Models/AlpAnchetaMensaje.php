<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAnchetaMensaje extends Model
{
    use SoftDeletes;

    public $table = 'alp_ancheta_mensaje';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_orden',
        'id_ancheta',
        'mensaje_de',
        'mensaje_para',
        'mensaje_mensaje',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_orden' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_orden' => 'required'
    ];
}
