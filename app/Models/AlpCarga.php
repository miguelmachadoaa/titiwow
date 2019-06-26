<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpCarga extends Model
{
    use SoftDeletes;

    public $table = 'alp_carga';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'data_subida',
        'data_respaldo',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required'
    ];
}
