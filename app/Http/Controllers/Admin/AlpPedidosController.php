<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpAlmacenRol;
use App\Models\AlpDirecciones;
use App\Models\AlpAlmacenDespacho;
use App\Models\AlpAlmacenFormaEnvio;
use App\Models\AlpAlmacenFormaPago;
use App\Models\AlpFormaspago;
use App\Models\AlpFormasenvio;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpInventario;
use App\Models\AlpCategorias;
use App\Models\AlpMarcas;
use App\User;
use App\State;
use App\City;
use App\Barrio;

use App\Models\AlpAlmacenesUser;
use App\Http\Requests\AlmacenesRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Imports\InvitacionesImport;
use App\Imports\AlmacenImport;
use Maatwebsite\Excel\Facades\Excel;

use Activation;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpPedidosController extends JoshController
{

   
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('almacenes/index ');

        }else{

          activity()
          ->log('almacenes/index');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        $almacenes = AlpAlmacenes::all();

        

        $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->where('alp_productos.destacado', '1')
          ->orderBy('alp_productos.nombre_producto')
          ->limit(12)
          ->get();

          $categorias=AlpCategorias::orderBy('nombre_categoria')->get();

          $marcas=AlpMarcas::orderBy('nombre_marca')->get();

        // Show the page
        return view('admin.pedidos.index', compact('almacenes', 'productos', 'categorias', 'marcas'));
    }

    public function data()
    {
       
        $almacenes = AlpAlmacenes::all();
         
        $data = array();

        foreach($almacenes as $row){

          if ($row->estado_registro=='1') {

             $estatus=" <div class='estatus_".$row->id."'>
             <button data-url='".secure_url('admin/almacenes/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='0' class='btn btn-xs btn-danger estatus'>Desactivar</button>
            </div>";

          }else{

                        $estatus="<div class='estatus_".$row->id."'>
            <button data-url='".secure_url('admin/almacenes/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='1' class='btn btn-xs btn-success estatus'>Activar</button>
             </div>";

           }

        $actions = " 
              <a href='".secure_url('admin/almacenes/'.$row->id.'/gestionar')."'>
                              <i class='livicon' data-name='gears' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Gestionar Almacen'></i>
                      </a>


                      <a href='".secure_url('admin/almacenes/'.$row->id.'/upload')."'>
                              <i class='livicon' data-name='arrow-circle-up' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Agregar Productos'></i>
                      </a>


                      <!--a href='".secure_url('admin/almacenes/'.$row->id.'/roles')."'>
                              <i class='livicon' data-name='users' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Empresa'></i>
                      </a-->
                      

                      <a href='".secure_url('admin/almacenes/'.$row->id.'/edit')."'>
                              <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Almacen'></i>
                      </a>


                        <a href='".secure_url('admin/almacenes/'.$row->id.'')."'>
                              <i class='livicon' data-name='eye-open' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Datos del almacen'></i>
                      </a>  

                      <a href='".secure_url('admin/almacenes/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'> <i class='livicon' data-name='remove-alt' data-size='18'
                        data-loop='true' data-c='#f56954' data-hc='#f56954' title='Eliminar'></i>

                      </a>";

                      $ap=AlpAlmacenProducto::where('id_almacen', $row->id)->groupBy('id_producto')->get();

                      if ($row->tipo_almacen==0) {

                        $tipo='Normal';
                        # code...
                      }else{

                        $tipo='Nomina';
                      }

               $data[]= array(
                 $row->id, 
                 $row->nombre_almacen, 
                 $row->descripcion_almacen,
                 count($ap),
                 $row->hora, 
                 $tipo,
                 $row->minimo_compra, 
                 $estatus, 
                 $actions
              );

          }

          return json_encode( array('data' => $data ));
          
      }
 
    public function create()
    {

      if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->log('almacenes/create ');

        }else{

          activity()
          ->log('almacenes/create');

        }

        $almacen=AlpAlmacenes::get();

        $states=State::where('config_states.country_id', '47')->get();

        $cities=City::get();


         $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

            $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();



        // Show the page
        return view ('admin.pedidos.create', compact('almacen', 'states', 'cities','t_documento', 'estructura'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(AlmacenesRequest $request)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->all())->log('almacenes/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('almacenes/store');

        }
        
        $user_id = Sentinel::getUser()->id;

        if ($request->defecto=='1') {

          $as=AlpAlmacenes::get();

          foreach ($as as $a) {

            $a->update(['defecto'=>'0']);
            # code...
          }

          
        }

        $data = array(
            'nombre_almacen' => $request->nombre_almacen, 
            'descripcion_almacen' => $request->descripcion_almacen, 
            'defecto' => $request->defecto, 
            'id_city' => $request->city_id, 
            'hora' => $request->hora, 
            'correos' => $request->correos, 
            'minimo_compra' => $request->minimo_compra, 
            'tipo_almacen' => $request->tipo_almacen, 
            'formato' => $request->formato, 
            'descuento_productos' => $request->descuento_productos, 
            'mensaje_promocion' => $request->mensaje_promocion, 
            'id_user' =>$user_id
        );
         
        $almacen=AlpAlmacenes::create($data);



        $data_direccion = array(
        'id_client'=>'A'.$almacen->id,
        'titulo'=>$request->titulo,
        'city_id'=>$request->city_id,
        'id_estructura_address'=>$request->id_estructura_address,
        'principal_address'=>$request->principal_address,
        'secundaria_address'=>$request->secundaria_address,
        'edificio_address'=>$request->edificio_address,
        'detalle_address'=>$request->detalle_address,
        'barrio_address'=>$request->barrio_address,
        'id_barrio'=>$request->id_barrio,
        'notas'=>$request->notas
        );

        $dir=AlpDirecciones::create($data_direccion);
      

        if ($almacen->id) {

            return redirect('admin/almacenes')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/almacenes')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {

      if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/edit');


        }
       
       $almacen = AlpAlmacenes::where('id', $id)->first();

      

       $states=State::where('config_states.country_id', '47')->get();

       if ($almacen->id_city=='0') {
         
          $almacen->id_state=0;

       }else{

        $city=City::where('id', $almacen->id_city)->first();

         $almacen->id_state=$city->state_id;

       }

        //dd($almacen);

       //

      $cities=City::get();

       //$listaestados=State::pluck('state_name', 'id');

       // $listaestados[0]='Todos';

       // $listaciudades=City::pluck('city_name', 'id');

       // $listaciudades[0]='Todos';
       // 
        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

        $direccion=AlpDirecciones::where('id_client', 'A'.$almacen->id)->first();



        return view('admin.pedidos.edit', compact('almacen', 'states', 'cities', 'estructura', 'direccion'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(AlmacenesRequest $request, $id)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('almacenes/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('almacenes/update');


        }

        //dd($request->all());

         if ($request->defecto=='1') {

          $as=AlpAlmacenes::get();

          foreach ($as as $a) {

            $a->update(['defecto'=>'0']);
            # code...
          }

          
        }

                $data = array(
                'nombre_alamcen' => $request->nombre_alamcen, 
                'descripcion_alamcen' => $request->descripcion_alamcen,
                'defecto' => $request->defecto, 
                'id_city' => $request->city_id,
                'hora' => $request->hora, 
                'correos' => $request->correos, 
                'minimo_compra' => $request->minimo_compra, 
                'descuento_productos' => $request->descuento_productos, 
                'mensaje_promocion' => $request->mensaje_promocion, 
                'formato' => $request->formato, 
                'tipo_almacen' => $request->tipo_almacen
                );

               // dd($data);


       $almacen = AlpAlmacenes::find($id);
    
        $almacen->update($data);

         $data_direccion = array(
          'id_client'=>'A'.$almacen->id,
        'titulo'=>$request->titulo,
        'city_id'=>$request->city_id,
        'id_estructura_address'=>$request->id_estructura_address,
        'principal_address'=>$request->principal_address,
        'secundaria_address'=>$request->secundaria_address,
        'edificio_address'=>$request->edificio_address,
        'detalle_address'=>$request->detalle_address,
        'barrio_address'=>$request->barrio_address,
        'id_barrio'=>$request->id_barrio,
        'notas'=>$request->notas
        );

        $direccion=AlpDirecciones::where('id_client', 'A'.$almacen->id)->first();

        if (isset($direcion->id)) {
          # code...
          $direccion->update($data_direccion);
        }else{


          AlpDirecciones::create($data_direccion);
        }

        if ($almacen->id) {

            return redirect('admin/almacenes')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/almacenes')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

    /**
     * Delete confirmation for the given group.
     *
     * @param  int $id
     * @return View
     */
    public function getModalDelete($id = null)
    {
        $model = 'empresas';
        $confirm_route = $error = null;
        try {
            // Get group inempresastion
            
            $empresas = AlpAlmacenes::find($id);

            $confirm_route = route('admin.pedidos.delete', ['id' => $empresas->id]);

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('Ha ocurrido un error al eliminar registro');
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    /**
     * Delete the given group.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('empresas/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('empresas/destroy');


        }


        try {
            // Get group inempresastion
           
            $empresas = AlpAlmacenes::find($id);

            // Delete the group
            $empresas->delete();

            // Redirect to the group management page
            return Redirect::route('admin.pedidos.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.pedidos.index')->with('error', trans('Error al eliminar el registro'));
        }
    }


     public function gestionar($id)
    {

      if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/edit');

        }

       
       $productos = AlpProductos::get();

       $almacen = AlpAlmacenes::where('id', $id)->first();

       $cs=AlpAlmacenProducto::where('id_almacen', $id)->get();

       $inventario=$this->inventario();

       //dd($inventario);

       $check = array();

       foreach ($cs as $c) {

        $check[$c->id_producto]=1;
         # code...
       }



        return view('admin.pedidos.gestionar', compact('almacen', 'productos', 'check', 'inventario'));
    }


     public function postgestionar(Request $request, $id)
    {
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->withProperties(['id'=>$id])->log('almacen/postgestionar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/postgestionar');

        }

        $input=$request->all();

        //dd($input);

        AlpAlmacenProducto::where('id_almacen', '=', $id)->delete();

        foreach ($input as $key => $value) {
          

          if (substr($key, 0, 2)=='p_') {

            #echo $key.':'.$value.'<br>';

            $par=explode('p_', $key);

            $data = array(
              'id_producto' => $par[1], 
              'id_almacen' => $id, 
              'id_user' => $user->id, 
            );

            AlpAlmacenProducto::create($data);
            
          }

        }
       
        return Redirect::route('admin.pedidos.index')->with('success', trans('Se ha creado satisfactoriamente'));
    }




     public function roles($id)
    {

      if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/roles ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/roles');

        }


        $roles = Sentinel::getRoleRepository()->all();


       

       $almacen = AlpAlmacenes::where('id', $id)->first();

       $cs=AlpAlmacenRol::where('id_almacen', $id)->get();

       $check = array();


       foreach ($cs as $c) {

        $check[$c->id_rol]=1;
         # code...
       }

        return view('admin.pedidos.roles', compact('almacen', 'roles', 'check'));
    }


     public function postroles(Request $request, $id)
    {
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->withProperties(['id'=>$id])->log('almacen/postroles ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/postroles');

        }

        $input=$request->all();


        AlpAlmacenRol::where('id_almacen', '=', $id)->delete();

        foreach ($input as $key => $value) {
          

          if (substr($key, 0, 2)=='p_') {

            #echo $key.':'.$value.'<br>';

            $par=explode('p_', $key);

            $data = array(
              'id_rol' => $par[1], 
              'id_almacen' => $id, 
              'id_user' => $user->id, 
            );

            AlpAlmacenRol::create($data);
            
          }

        }
       
        return Redirect::route('admin.pedidos.index')->with('success', trans('Se ha creado satisfactoriamente'));
    }



    private function inventario()
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->log('AlpInventarioController/inventario ');

        }else{

          activity()
          ->log('AlpInventarioController/inventario');

        }


      $entradas = AlpInventario::groupBy('id_producto')->groupBy('id_almacen')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->whereNull('deleted_at')
              ->get();

              $inv = array();
              $inv2 = array();

             foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

                $inv2[$row->id_producto][$row->id_almacen]=$row->cantidad_total;

              }




            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->groupBy('id_almacen')
              ->where('operacion', '2')
              ->whereNull('deleted_at')
              ->get();

              foreach ($salidas as $row) {
                
                //$inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

                  if (  isset( $inv2[$row->id_producto][$row->id_almacen])) {
                    $inv2[$row->id_producto][$row->id_almacen]= $inv2[$row->id_producto][$row->id_almacen]-$row->cantidad_total;
                  }else{

                    $inv2[$row->id_producto][$row->id_almacen]= 0;
                  }

              
                //$inv2[$row->id_producto][$row->id_almacen]= $row->cantidad_total;

            }

           // dd($inv2);

            return $inv2;
      
    }



     public function upload($id)
    {

       if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/edit');

        }
       

       $almacen = AlpAlmacenes::where('id', $id)->first();

       $almacenes = AlpAlmacenes::get();


        return view('admin.pedidos.upload', compact('almacen', 'almacenes'));
    }


     public function postupload(Request $request, $id)
    {
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->withProperties(['id'=>$id])->log('almacen/postgestionar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/postgestionar');

        }

        $input=$request->all();

         $archivo = $request->file('file_update');

        \Session::put('almacen', $id);
        
        \Session::put('inventario', $this->inventario());

        \Session::put('cities', $request->cities);

        Excel::import(new AlmacenImport, $archivo);
        

       
       
        return Redirect::route('admin.pedidos.index')->with('success', trans('Se ha creado satisfactoriamente'));
    }



     public function show($id)
    {
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/edit');

        }


         if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        $productos = AlpProductos::select('alp_productos.*')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->whereNull('alp_almacen_producto.deleted_at')
        ->where('alp_almacen_producto.id_almacen', '=', $id)
        ->groupBy('alp_productos.id')
        ->get();

        $almacen = AlpAlmacenes::where('id', $id)->first();

        $inventario=$this->inventario();

        $almacen_formas_pago=AlpAlmacenFormaPago::select('alp_almacen_formas_pago.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
        ->join('alp_formas_pagos', 'alp_almacen_formas_pago.id_forma_pago', '=' ,'alp_formas_pagos.id')
        ->where('alp_almacen_formas_pago.id_almacen', $id)->get();

        $almacen_formas_envio=AlpAlmacenFormaEnvio::select('alp_almacen_formas_envio.*', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios')
        ->join('alp_formas_envios', 'alp_almacen_formas_envio.id_forma_envio', '=', 'alp_formas_envios.id')
        ->where('alp_almacen_formas_envio.id_almacen', $id)->get();

        $despachos=AlpAlmacenDespacho::where('id_almacen', $id)->get();

        $listaestados=State::pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

        $listabarrios=Barrio::pluck('barrio_name', 'id');

         $listabarrios[0]='Todos';

       $formas_envio=AlpFormasenvio::get();

       $formas_pago=AlpFormaspago::get();

       $states=State::where('config_states.country_id', '47')->get();

        return view('admin.pedidos.show', compact('almacen', 'productos',  'inventario', 'formas_pago', 'formas_envio', 'listaestados', 'listaciudades', 'listabarrios','despachos', 'states', 'almacen_formas_pago', 'almacen_formas_envio'));
    }

      public function addformenvio(Request $request)
    {

         if (Sentinel::check()) {
          $user = Sentinel::getUser();
           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('almacenes/addformaenvio ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('almacenes/addformaenvio');
        }

         $user_id = Sentinel::getUser()->id;
        $data = array(
            'id_almacen' => $request->id_almacen, 
            'id_forma_envio' => $request->id_forma_envio, 
            'user_id' => $request->user_id
        );

        AlpAlmacenFormaEnvio::create($data);

        $almacen_formas_envio=AlpAlmacenFormaEnvio::select('alp_almacen_formas_envio.*', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios')
        ->join('alp_formas_envios', 'alp_almacen_formas_envio.id_forma_envio', '=', 'alp_formas_envios.id')
        ->where('alp_almacen_formas_envio.id_almacen', $request->id_almacen)->get();

          $view= View::make('admin.pedidos.listformasenvio', compact('almacen_formas_envio'));

      $data=$view->render();

      return $data;


    }


  public function delformaenvio(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('almacenes/addformaenvio ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('almacenes/addformaenvio');


        }

         $user_id = Sentinel::getUser()->id;
       

        $afe=AlpAlmacenFormaEnvio::where('id', $request->id)->first();

        $id_alamcen=$afe->id_almacen;

        $afe->delete();


        $almacen_formas_envio=AlpAlmacenFormaEnvio::select('alp_almacen_formas_envio.*', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios')
        ->join('alp_formas_envios', 'alp_almacen_formas_envio.id_forma_envio', '=', 'alp_formas_envios.id')
        ->where('alp_almacen_formas_envio.id_almacen', $id_alamcen)->get();

          $view= View::make('admin.pedidos.listformasenvio', compact('almacen_formas_envio'));

      $data=$view->render();

      return $data;


    }



      public function addformapago(Request $request)
    {

         if (Sentinel::check()) {
          $user = Sentinel::getUser();
           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('almacenes/addformapago ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('almacenes/addformapago');
        }

         $user_id = Sentinel::getUser()->id;

        $data = array(
            'id_almacen' => $request->id_almacen, 
            'id_forma_pago' => $request->id_forma_pago, 
            'user_id' => $request->user_id
        );

        AlpAlmacenFormaPago::create($data);

       $almacen_formas_pago=AlpAlmacenFormaPago::select('alp_almacen_formas_pago.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
        ->join('alp_formas_pagos', 'alp_almacen_formas_pago.id_forma_pago', '=' ,'alp_formas_pagos.id')
        ->where('alp_almacen_formas_pago.id_almacen', $request->id_almacen)->get();

          $view= View::make('admin.pedidos.listformaspago', compact('almacen_formas_pago'));

      $data=$view->render();

      return $data;


    }


  public function delformapago(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('almacenes/delformapago ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('almacenes/delformapago');


        }

         $user_id = Sentinel::getUser()->id;
       

        $afp=AlpAlmacenFormaPago::where('id', $request->id)->first();

        $id_alamcen=$afp->id_almacen;

        $afp->delete();


        $almacen_formas_pago=AlpAlmacenFormaPago::select('alp_almacen_formas_pago.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
        ->join('alp_formas_pagos', 'alp_almacen_formas_pago.id_forma_pago', '=' ,'alp_formas_pagos.id')
        ->where('alp_almacen_formas_pago.id_almacen', $id_alamcen)->get();


          $view= View::make('admin.pedidos.listformaspago', compact('almacen_formas_pago'));

      $data=$view->render();

      return $data;


    }





      public function adddespacho(Request $request)
    {

         if (Sentinel::check()) {
          $user = Sentinel::getUser();
           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('almacenes/adddespacho ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('almacenes/adddespacho');
        }

         $user_id = Sentinel::getUser()->id;

        $data = array(
            'id_almacen' => $request->id_almacen, 
            'id_city' => $request->id_city, 
            'id_state' => $request->id_state, 
            'id_barrio' => $request->id_barrio, 
            'user_id' => $request->user_id
        );

        AlpAlmacenDespacho::create($data);

       $despachos=AlpAlmacenDespacho::where('id_almacen', $request->id_almacen)->get();

        $listaestados=State::pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

        $listabarrios=Barrio::pluck('barrio_name', 'id');

         $listabarrios[0]='Todos';


          $view= View::make('admin.pedidos.listdespachos', compact('despachos', 'listaestados', 'listaciudades', 'listabarrios'));

      $data=$view->render();

      return $data;


    }


  public function deldespacho(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('almacenes/deldespacho ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('almacenes/deldespacho');


        }

         $user_id = Sentinel::getUser()->id;
       

        $afd=AlpAlmacenDespacho::where('id', $request->id)->first();

        $id_alamcen=$afd->id_almacen;

        $afd->delete();


        $despachos=AlpAlmacenDespacho::where('id_almacen', $id_alamcen)->get();

        $listaestados=State::pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

         $listabarrios=Barrio::pluck('barrio_name', 'id');

         $listabarrios[0]='Todos';

          $view= View::make('admin.pedidos.listdespachos', compact('despachos', 'listaestados', 'listaciudades', 'listabarrios'));

      $data=$view->render();

      return $data;


    }











      public function estatus(Request $request)
    {

       if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('almacenes/estatus ');

        }else{

          activity()
          ->withProperties($request->all())->log('almacenes/estatus');

        }

        $input = $request->all();

        $almacen=AlpAlmacenes::find($request->id);

        $data = array('estado_registro' => $request->estatus );

        $almacen->update($data);


        $view= View::make('admin.pedidos.estatus', compact('almacen'));

        $data=$view->render();
        
        return $data;
    }






    public function datacategorias($id)
    {

         $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->where('alp_productos.id_categoria_default', $id)
          ->orderBy('alp_productos.nombre_producto')
         // ->limit(12)
          ->get();

           $view= View::make('admin.pedidos.table', compact('productos'));

            $data=$view->render();

            return $data;
          
    }


    public function datamarcas($id)
    {

         $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->where('alp_productos.id_marca', $id)
          ->orderBy('alp_productos.nombre_producto')
         // ->limit(12)
          ->get();

           $view= View::make('admin.pedidos.table', compact('productos'));

            $data=$view->render();

            return $data;
          
    }


    public function databuscar($buscar)
    {

         $productos = AlpProductos::search($buscar)->select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
         // ->where('alp_productos.id_marca', $id)
          ->orderBy('alp_productos.nombre_producto')
          ->get();

           $view= View::make('admin.pedidos.table', compact('productos'));

            $data=$view->render();

            return $data;
          
    }








      public function addtocart($id)
    {

      if (!\Session::has('cart')) {
          \Session::put('cart', array());
        }

          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.id', $id)
          ->first();

          //dd($producto);

       $cart= \Session::get('cart');

       $descuento='1'; 

       $error='0'; 

       $precio = array();

       $inv=$this->inventario();

       $almacen='1';

       //dd($almacen);

       if (isset($producto->id)) {

       // $producto->precio_oferta=$request->price;

        $producto->cantidad=1;

       // $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

        if (isset($inv[$producto->id])) {


          if($inv[$producto->id]>=$producto->cantidad){

            $cart[$producto->slug]=$producto;

          }else{

            $error="No hay existencia suficiente de este producto";

          }

          # code...
        }else{

            $error="No hay existencia suficiente de este producto, en su ubicacion";

        }

       }else{

        $error="No encontro el producto";

       }

      \Session::put('cart', $cart);

      $view= View::make('admin.pedidos.listaorden', compact('producto', 'cart', 'error'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }











}