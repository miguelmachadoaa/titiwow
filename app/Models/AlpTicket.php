<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpTicket extends Model
{
    use SoftDeletes;

    public $table = 'alp_ticket';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'orden',
        'departamento',
        'urgencia',
        'caso',
        'titulo_ticket',
        'texto_ticket',
        'archivo',
        'origen',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'titulo_ticket' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_almacen' => 'required'
    ];
}
