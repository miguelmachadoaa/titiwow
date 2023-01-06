<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpTransacciones extends Model
{
    use SoftDeletes;

    public $table = 'alp_transacciones';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_orden',
        'referencia',
        'monto',
        'valor',
        'id_forma_pago',
        'tipo',
        'moneda',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_orden' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_orden' => 'required'
    ];


    public function formapago()
    {
        return $this->hasOne('App\Models\AlpFormaspago', 'id', 'id_forma_pago');
    }
}
