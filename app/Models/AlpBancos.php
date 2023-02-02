<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpBancos extends Model
{
    use SoftDeletes;

    public $table = 'alp_bancos';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_banco',
        'descripcion_banco',
        'codigo_banco',
        'estado_registro',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo_banco' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo_banco' => 'required'
    ];
}
