<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpDepartamento extends Model
{
    use SoftDeletes;

    public $table = 'alp_departamento';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_departamento',
        'descripcion_departamento',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_departamento' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_departamento' => 'required'
    ];
}
