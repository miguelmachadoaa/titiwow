<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpTrasportistas extends Model
{
    use SoftDeletes;

    public $table = 'alp_transportistas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_transportista',
        'descripcion_transportista',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_transportista' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_transportista' => 'required'
    ];
}
