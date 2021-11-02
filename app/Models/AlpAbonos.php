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
        'name',
        'nota',
        'completed',
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
