<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpEnvios extends Model
{
    use SoftDeletes;

    public $table = 'alp_envios';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_orden',
        'fecha_envio',        
        'costo',        
        'costo_base',        
        'costo_impuesto',        
        'estatus',
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
