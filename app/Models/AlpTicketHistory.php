<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class AlpTicketHistory extends Model
{
    use SoftDeletes;

    public $table = 'alp_ticket_history';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'id_ticket',
        'id_status',
        'notas',
        'json',
        'id_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_ticket' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_ticket' => 'required'
    ];
}
