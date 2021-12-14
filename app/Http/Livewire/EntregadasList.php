<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AlpOrdenes;
use App\RoleUser;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use Livewire\WithPagination;
use DB;
use Sentinel;

class EntregadasList extends Component
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

        
        if (Sentinel::check()) { 

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }

        $user = Sentinel::getUser();

        if (isset($user->id)) {
  
  
          if ($user->almacen=='0') {

            $entregadas = AlpOrdenes::when($this->search, function($query){
                return $query->where(function ($query){
                    $query->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $this->search . '%')
                    ->orwhere('alp_ordenes.referencia','like','%'.$this->search.'%')
                    ->orWhere('alp_ordenes.created_at','like','%'.$this->search.'%');
                });
            }) 
            ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
            ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
            ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
            ->select(
                'alp_ordenes.id as id',
                'alp_ordenes.origen as origen', 
                'alp_ordenes.id_almacen',
                'alp_ordenes.id_forma_pago',
                'alp_ordenes.estatus_pago as estatus_pago', 
                'alp_ordenes.monto_total as monto_total', 
                'alp_ordenes.referencia as referencia', 
                'alp_ordenes.created_at as created_at', 
                'users.first_name as first_name', 
                'users.last_name as last_name',
                'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
                'alp_almacenes.nombre_almacen as nombre_almacen')
            ->where('alp_ordenes.estatus', '3')
            ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');
        }else{
            
            $entregadas = AlpOrdenes::when($this->search, function($query){
                return $query->where(function ($query){
                    $query->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $this->search . '%')
                    ->orwhere('alp_ordenes.referencia','like','%'.$this->search.'%')
                    ->orWhere('alp_ordenes.created_at','like','%'.$this->search.'%');
                });
            }) 
            ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
            ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
            ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
            ->select(
                'alp_ordenes.id as id',
                'alp_ordenes.origen as origen', 
                'alp_ordenes.id_almacen',
                'alp_ordenes.estatus_pago as estatus_pago', 
                'alp_ordenes.monto_total as monto_total', 
                'alp_ordenes.referencia as referencia', 
                'alp_ordenes.created_at as created_at', 
                'users.first_name as first_name', 
                'users.last_name as last_name',
                'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
                'alp_almacenes.nombre_almacen as nombre_almacen')
            ->where('alp_ordenes.estatus', '3')
            ->where('alp_ordenes.id_almacen', '=', $user->almacen)
            ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        }
    }

        
        $entregadas = $entregadas->paginate($this->cantid);

        return view('livewire.entregadas-list',[
            'entregadas' => $entregadas,
            'id_rol' => $id_rol
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
