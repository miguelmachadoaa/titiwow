<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class AlpEstructuraAddress extends Model
{
    use SoftDeletes;

    public $table = 'alp_direcciones_estructura';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_estructura',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_estructura' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_estructura' => 'required'
    ];
}
