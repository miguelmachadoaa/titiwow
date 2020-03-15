<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpSaldo extends Model
{
    use SoftDeletes;

    public $table = 'alp_saldo';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_cliente',
        'saldo',
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
