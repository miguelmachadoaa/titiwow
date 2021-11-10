<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AlpOrdenes;
use App\Models\AlpClientes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpEstatusOrdenes;
use App\Models\AlpFormaspago;
use App\Models\AlpFormasenvio;
use App\RoleUser;
use Livewire\WithPagination;
use DB;
use Sentinel;

class TodasList extends Component
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
        $todas = AlpOrdenes::when($this->search, function($query){
            return $query->where(function ($query){
                $query->where('alp_ordenes.referencia','like','%'.$this->search.'%')
                    ->orWhere('users.first_name' ,'like','%'.$this->search.'%')
                    ->orWhere('alp_ordenes.origen' ,'like','%'.$this->search.'%')
                    ->orWhere('users.last_name','like','%'.$this->search.'%');
                });
        }) 
        ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
       // ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
       // ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
       // ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
       // ->join('config_cities', 'alp_almacenes.id_city', '=', 'config_cities.id')
       // ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
       // ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
        ->select(
            'alp_ordenes.id as id',
            'alp_ordenes.id_cliente as id_cliente',
            'alp_ordenes.origen as origen', 
            'alp_ordenes.estatus_pago as estatus_pago', 
            'alp_ordenes.monto_total as monto_total', 
            'alp_ordenes.referencia as referencia', 
            'alp_ordenes.estatus as estatus', 
            'alp_ordenes.id_forma_envio as id_forma_envio',
            'alp_ordenes.id_forma_pago as id_forma_pago',
            'alp_ordenes.created_at as created_at', 
            'users.first_name as first_name', 
            'users.last_name as last_name')
          /*  'alp_clientes.telefono_cliente as telefono_cliente', 
            'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
            'alp_almacenes.nombre_almacen as nombre_almacen',
            'config_cities.city_name as city_name',
            'alp_formas_envios.nombre_forma_envios as forma_envio',
            'alp_formas_pagos.nombre_forma_pago as forma_pago')*/

        ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        
        $todas = $todas->paginate($this->cantid);

        foreach($todas as $row){
            //Agrega Cupones
            
            $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

            if (isset($descuento->id)) {
                $row->codigo_cupon=$descuento->codigo_cupon;

            }else{

            $row->codigo_cupon='N/A';

            }
            //Agrega Telefono

            $telefono_cliente=AlpClientes::where('id_user_client', $row->id_cliente)->first();

            if (isset($telefono_cliente->id)) {
            $row->telefono_cliente=$telefono_cliente->telefono_cliente;

            }else{

            $row->telefono_cliente='N/A';

            }
            //Agrega Estado

            $estatus_nombre=AlpEstatusOrdenes::where('id', $row->estatus)->first();

            if (isset($estatus_nombre->id)) {
            $row->estatus_nombre=$estatus_nombre->estatus_nombre;

            }else{

            $row->estatus_nombre='N/A';

            }
            //Agrega Forma Envio

            $forma_envio=AlpFormasenvio::where('id', $row->id_forma_envio)->first();

            if (isset($forma_envio->id)) {
            $row->forma_envio=$forma_envio->nombre_forma_envios;

            }else{

            $row->forma_envio='N/A';

            }
            //Agrega Forma Pago

            $forma_pago=AlpFormaspago::where('id', $row->id_forma_pago)->first();

            if (isset($forma_pago->id)) {
            $row->forma_pago=$forma_pago->nombre_forma_pago;

            }else{

            $row->forma_envio='N/A';

            }
        }

        return view('livewire.todas-list',[
            'todas' => $todas,
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
