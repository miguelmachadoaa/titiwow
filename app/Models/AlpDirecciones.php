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
        'nickname_address',
        'calle_address',
        'calle2_address',
        'codigo_postal_address',
        'telefono_address',
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
