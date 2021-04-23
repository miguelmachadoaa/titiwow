<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAbonosTipo extends Model
{
    use SoftDeletes;

    public $table = 'alp_tipo_bono';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_bono',
        'descripcion_bono',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_bono' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo_abono' => 'required'
    ];
}
