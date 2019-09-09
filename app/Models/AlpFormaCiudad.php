<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpFormaCiudad extends Model
{
    use SoftDeletes;

    public $table = 'alp_forma_ciudad';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_forma',       
        'id_ciudad',       
        'id_rol',       
        'dias',       
        'hora',       
        'costo',       
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_forma' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_forma' => 'required'
    ];
}
