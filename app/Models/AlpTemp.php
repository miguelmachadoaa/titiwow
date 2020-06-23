<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpTemp extends Model
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
            'alp_temp.id' => 10,
            'alp_temp.referencia  ' => 5,
        ]
    ];

    public $table = 'alp_temp';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'referencia',
        'cliente',
        'telefono',
        'forma_envio',
        'forma_pago',
        'total',
        'codigo_oracle',
        'cupon',
        'factura',
        'tracking',
        'almacen',
        'ciudad',
        'creado',
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
