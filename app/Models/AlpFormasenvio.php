<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpFormasenvio extends Model
{
    use SoftDeletes;

    public $table = 'alp_formas_envios';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'sku',
        'email',
        'nombre_forma_envios',
        'descripcion_forma_envios',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_forma_envios' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_forma_envios' => 'required'
    ];
}
