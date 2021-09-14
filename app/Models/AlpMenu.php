<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpMenu extends Model
{
    use SoftDeletes;

    public $table = 'alp_menu';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_menu',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_menu' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_menu' => 'required'
    ];
}
