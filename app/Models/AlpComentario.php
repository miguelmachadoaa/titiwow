<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpComentario extends Model
{
    use SoftDeletes;

    public $table = 'alp_comentario';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_ticket',
        'titulo_ticket',
        'texto_ticket',
        'archivo',
        'id_padre',
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
