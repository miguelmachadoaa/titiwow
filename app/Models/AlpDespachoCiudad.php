<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpDespachoCiudad extends Model
{
    use SoftDeletes;

    public $table = 'alp_despacho_ciudad';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_ciudad',       
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_ciudad' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_ciudad' => 'required'
    ];
}
