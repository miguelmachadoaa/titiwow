<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpEnviosEstatus extends Model
{
    use SoftDeletes;

    public $table = 'alp_envios_status';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'estatus_envio_nombre',
        'estatus_envio_descripcion',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'estatus_envio_nombre' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'estatus_envio_nombre' => 'required'
    ];
}
