<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpTDocumento extends Model
{
    use SoftDeletes;

    public $table = 'alp_tipo_documento';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_tipo_documento',
        'abrev_tipo_documento',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];
}
