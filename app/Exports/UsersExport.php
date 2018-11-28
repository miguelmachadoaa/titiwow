<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       /* return User::whereDate('users.created_at', '=', Carbon::today())
        ->join('role_users','users.id','=','role_users.user_id')
                ->whereIn('role_users.role_id', [9, 10, 11,12])
                ->get();*/

        return User::all();
    }
}
