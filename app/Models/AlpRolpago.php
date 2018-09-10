<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpRolpago extends Model
{
    use SoftDeletes;

    public $table = 'alp_rol_pago';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_rol',
        'id_forma_pago',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_rol' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_rol' => 'required'
    ];
}
