<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpMarcas extends Model
{
    use SoftDeletes;

    public $table = 'alp_marcas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_marca',
        'descripcion_marca',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_marca' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_marca' => 'required'
    ];
}
