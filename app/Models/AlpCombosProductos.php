<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpCombosProductos extends Model
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
            'alp_combos_productos.id_combo' => 10,
           // 'alp_productos.descripcion_corta' => 3,
        ]
    ];

    public $table = 'alp_combos_productos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_combo',
        'id_producto',
        'cantidad',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_combo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_combo' => 'required'
    ];

}
