<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpSliders extends Model
{
    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

     /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'alp_slider.nombre_slider' => 10,
            'alp_slider.descripcion_slider  ' => 5,
            'alp_slider.presentacion_slider' => 4,
           // 'alp_productos.descripcion_corta' => 3,
        ]
    ];

    public $table = 'alp_slider';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_slider',
        'descripcion_slider',
        'imagen_slider',
        'order',
        'link_slider',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_slider' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_slider' => 'required'
    ];

}
