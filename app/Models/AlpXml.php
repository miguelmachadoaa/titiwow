<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class AlpXml extends Model
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
            'alp_xml.id_producto' => 10,
            'alp_xml.id_rol  ' => 5,
        ]
    ];

    public $table = 'alp_xml';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_producto',
        'id_rol',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_producto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_producto' => 'required'
    ];

}
