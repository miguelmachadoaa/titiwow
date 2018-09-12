<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpEstatusOrdenes extends Model
{
    use SoftDeletes;

    public $table = 'alp_ordenes_estatus';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'estatus_nombre',
        'descripcion_estatus',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'estatus_nombre' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'estatus_nombre' => 'required'
    ];
}
