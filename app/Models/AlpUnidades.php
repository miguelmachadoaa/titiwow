<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpUnidades extends Model
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
            'alp_unidades.nombre_unidad' => 10,
            'alp_unidades.descripcion_unidad  ' => 5,
           // 'alp_productos.descripcion_unidad_corta' => 3,
        ]
    ];

    public $table = 'alp_unidades';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'nombre_unidad',
        'descripcion_unidad',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_unidad' => 'string'
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
