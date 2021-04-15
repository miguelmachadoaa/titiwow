<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class AlpCasos extends Model
{
    use SoftDeletes;

    public $table = 'alp_casos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_caso',
        'descripcion_caso',
        'id_user',
        'estado_registro'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_caso' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_caso' => 'required'
    ];
}
