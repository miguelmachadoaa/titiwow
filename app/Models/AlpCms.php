<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlpCms extends Model
{
    use SoftDeletes;

    public $table = 'alp_cms';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'titulo_pagina',
        'texto_pagina',
        'seo_titulo',
        'seo_descripcion',
        'slug',
        'robots',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'titulo_pagina' => 'string',
        'texto_pagina' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'titulo_pagina' => 'required',
        'texto_pagina' => 'required'
    ];
}
