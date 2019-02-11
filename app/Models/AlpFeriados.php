<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use DB;


class AlpFeriados extends Model
{
    use SoftDeletes;

    public $table = 'alp_feriados';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'feriado',
        'estado_registro',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'feriado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'feriado' => 'required'
    ];

    public static function  feriados()
    {

        $feriados =  DB::table('alp_feriados')->get();

        $fe = array( );

        foreach ($feriados as $f) {

            $fe[$f->feriado]=1;

        }

        return $fe;
    }
}
