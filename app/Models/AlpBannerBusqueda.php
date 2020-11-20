<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpBannerBusqueda extends Model
{
    use SoftDeletes;

    public $table = 'alp_banner_busqueda';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'termino',
        'banner_categoria',
        'banner_movil_categoria',
        'enlace_categoria',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'termino' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'termino' => 'required'
    ];
}
