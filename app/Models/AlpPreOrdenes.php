<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpPreOrdenes extends Model
{
    use SoftDeletes;

    public $table = 'alp_preordenes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'referencia',
        'id_cliente',
        'monto_total',
        'monto_total_base',
        'preferencia_id',
        'json',
        'estatus',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'referencia' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'referencia' => 'required'
    ];
}
