<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class UsersExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        
    }



    public function query()
    {
       return User::query()->select('users.*')
          ->whereDate('users.created_at', '>', $this->desde)
          ->whereDate('users.created_at', '<', $this->hasta);

        
    }
}
