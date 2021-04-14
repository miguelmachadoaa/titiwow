<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;

use Nicolaslopezj\Searchable\SearchableTrait;

class AlpViewOrdenes extends Model
{
    use Notifiable;
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'view_ordenes.id' => 10,
            'view_ordenes.referencia' => 5,
            'view_ordenes.origen' => 4,
        ]
    ];

    public $table = 'view_ordenes';
   
}
