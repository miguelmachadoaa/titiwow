<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AlpOrdenes;
use Livewire\WithPagination;
use DB;

class AprobadosList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search ="";

    public $cantid = 10;

    public $sortBy = 'id';
    
    public $sortAsc = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => false]
    ];

    public function render()
    {
        $aprobados = AlpOrdenes::when($this->search, function($query){
            return $query->where(function ($query){
                $query->where('alp_ordenes.referencia','like','%'.$this->search.'%')
                    ->orWhere('users.first_name' ,'like','%'.$this->search.'%')
                    ->orWhere('users.last_name','like','%'.$this->search.'%');
                });
        })
        ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
        ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
        ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
        ->join('config_cities', 'alp_almacenes.id_city', '=', 'config_cities.id')
        ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
        ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
        ->select(
            'alp_ordenes.id as id',
            'alp_ordenes.origen as origen', 
            'alp_ordenes.estatus_pago as estatus_pago', 
            'alp_ordenes.monto_total as monto_total', 
            'alp_ordenes.referencia as referencia', 
            'alp_ordenes.created_at as created_at', 
            'users.first_name as first_name', 
            'users.last_name as last_name',
            DB::raw('CONCAT(users.first_name, " ", users.last_name) as cliente'), 
            'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
            'alp_almacenes.nombre_almacen as nombre_almacen',
            'config_cities.city_name as city_name',
            'alp_formas_envios.nombre_forma_envios as forma_envio',
            'alp_formas_pagos.nombre_forma_pago as forma_pago')
       // ->where('alp_ordenes.estatus', '5')
        ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        #$query = $aprobados->toSql();

        $aprobados = $aprobados->paginate($this->cantid);

        return view('livewire.aprobados-list',[
            'aprobados' => $aprobados
        ]);


    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy( $field)
    {
        if($field == $this->sortBy){
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }
}
