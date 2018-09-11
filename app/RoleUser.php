<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RoleUser extends Model
{
 

    public $table = 'role_users';
    

    


    public $fillable = [
        'role_id',
        'user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}
