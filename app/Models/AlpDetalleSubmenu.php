<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpDetalleSubmenu extends Model
{
    use SoftDeletes;

    public $table = 'alp_menu_detalles';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'name',
        'slug',
        'parent',
        'order',
        'enabled',
        'open',
        'id_menu'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'slug' => 'required'
    ];
}
