<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AlpClientes;
use App\User;
use App\RoleUser;
use App\City;
use Livewire\WithPagination;
use DB;
use Sentinel;

class ClientesList extends Component
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

            $clientes = User::when($this->search, function($query){
                return $query->where(function ($query){
                    $query->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $this->search . '%')
                        ->orwhere('alp_clientes.telefono_cliente','like','%'.$this->search.'%');
                    });
            }) 
            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->select('users.*','roles.name as name_role',
            'alp_clientes.estado_registro as estado_registro',
            'alp_clientes.telefono_cliente as telefono_cliente',
            'alp_clientes.marketing_sms as marketing_sms',
            'alp_clientes.marketing_email as marketing_email',
            'alp_clientes.deleted_at as cliente_deleted_at'
        )

            ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');
        
        $clientes = $clientes->paginate($this->cantid);
        return view('livewire.clientes-list',[
            'clientes' => $clientes,
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

    public function entregarOrden( $id){

        if (Sentinel::check()) {

            $user = Sentinel::getUser();
  
             activity($user->full_name)
                          ->performedOn($user)
                          ->causedBy($user)
                          ->withProperties($id)->log('AlpOrdenesController/enviar');
  
          }else{
  
            activity()
            ->withProperties($id)->log('AlpOrdenesController/enviar');
  
  
          }
  
  
  
          $user_id = Sentinel::getUser()->id;
  
        //$configuracion = AlpConfiguracion::where('id','1')->first();
  
          //$input = $request->all();
  
          //var_dump($input);
  
          $data_history = array(
              'id_orden' => $id, 
              'id_status' => '3', 
              'notas' => 'Orden Entregada, Actualizada Manualmente', 
              'id_user' => $user_id 
          );
  
          $data_update_orden = array(
              'estatus' =>'3'
          );
  
           
          $history=AlpOrdenesHistory::create($data_history);
  
          $orden=AlpOrdenes::find($id);
  
          $orden->update($data_update_orden);
  
         

    }


}
