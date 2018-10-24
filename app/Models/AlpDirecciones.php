<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpDirecciones extends Model
{
    use SoftDeletes;

    public $table = 'alp_direcciones';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_client',
        'city_id',
        'id_estructura_address',
        'principal_address',
        'secundaria_address',
        'edificio_address',
        'detalle_address',
        'barrio_address',
        'notas',
        'default_address',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nickname_address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nickname_address' => 'required'
    ];
}
