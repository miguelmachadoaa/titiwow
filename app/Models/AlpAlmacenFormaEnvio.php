<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAlmacenFormaEnvio extends Model
{
    use SoftDeletes;

    public $table = 'alp_almacen_formas_envio';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_almacen',
        'id_forma_envio',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_almacen' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_almacen' => 'required'
    ];
}
