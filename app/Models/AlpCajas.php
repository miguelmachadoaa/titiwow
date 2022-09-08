<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpCajas extends Model
{
    use SoftDeletes;

    public $table = 'alp_cajas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'monto_inicial',
        'monto_final',
        'fecha_inicio',
        'fecha_cierre',
        'observacions',
        'monto_final',
        'estado_registro',
        'id_user'
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

      public function cajero()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }
}
