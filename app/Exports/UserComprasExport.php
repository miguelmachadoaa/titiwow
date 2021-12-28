<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpLifeMiles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class UserComprasExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function view(): View
    {


        $usercompras = AlpOrdenes::select(
            'alp_ordenes.*',
            DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
            DB::raw('SUM(alp_ordenes.monto_total)  as total_compras'),
            DB::raw('count(alp_ordenes.id)  as cantidad_compras'),
            'users.email as email', 
            'users.first_name as first_name', 
            'users.last_name as last_name')
            ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
            ->groupBy('users.id')
            ->orderBy('alp_ordenes.id', 'desc')
            ->where('alp_ordenes.estatus', '<>', '4')
            ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
            ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
            ->get();

           ## dd($usercompras);

        return view('admin.exports.usercompras', [
            'usercompras' => $usercompras
        ]);
    }
}

