<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpRolConfiguracion extends Model
{
    use SoftDeletes;

    public $table = 'alp_rol_configuracion';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_rol',
        'precio',
        'referido',
        'empresa',
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
