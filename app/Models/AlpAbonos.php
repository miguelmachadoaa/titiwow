<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpAbonos extends Model
{
    use SoftDeletes;

    public $table = 'alp_abonos';        
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'codigo_abono',
        'valor_abono',
        'fecha_final',
        'origen',
        'token',
        'id_orden',
        'motivo',
        'notas',
        'tipo_abono',
        'id_almacen',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo_abono' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo_abono' => 'required'
    ];
}
