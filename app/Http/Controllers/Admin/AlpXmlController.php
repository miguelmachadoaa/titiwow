<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpAlmacenRol;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpInventario;
use App\Models\AlpXml;
use App\User;
use App\State;
use App\City;

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


class AlpXmlController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index(Request $request)
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

        $input=$request->all();
        
        if ($request->method()=='POST') {

             $user_id = Sentinel::getUser()->id;

              AlpXml::where('estado_registro', '=', '1')->delete();

              foreach ($input as $key => $value) {
                
                if (substr($key, 0, 2)=='p_') {

                  $par=explode('p_', $key);

                  $data = array(
                    'id_producto' => $par[1], 
                    'id_rol' => $request->id_rol, 
                    'id_user' => $user->id, 
                  );

                  AlpXml::create($data);
                  
                }

              }

            //dd($input);

          }//Fin si llegan datos por post




        $productos = AlpProductos::whereNull('alp_productos.deleted_at')->get();

        $almacen = AlpAlmacenes::where('id', '1')->first();

        $inventario=$this->inventario();

        $cs=AlpXml::get();

        $cs1=AlpXml::first();

        if (isset($cs1->id)) {

            \Session::put('rolxml', $cs1->id_rol);

            $rolxml=$cs1->id_rol;

          }else{

            \Session::put('rolxml', '9');

            $rolxml=9;

          }


        $check = array();

        foreach ($cs as $c) {

          $check[$c->id_producto]=1;
           # code...
         }


         $roles = DB::table('roles')->select('id', 'name')->where('id','>',8)->get();

          $precio = array();

         foreach ($productos as  $row) {
                    
            $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $rolxml)->first();

            if (isset($pregiogrupo->id)) {
               
                $precio[$row->id]['precio']=$pregiogrupo->precio;
                $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                $precio[$row->id]['pum']=$pregiogrupo->pum;

            }

        }

        $descuento=1;

        $prods = array( );

        foreach ($productos as $producto) {

      if ($descuento=='1') {

        if (isset($precio[$producto->id])) {
          # code...
         
          switch ($precio[$producto->id]['operacion']) {

            case 1:

              $producto->precio_oferta=$producto->precio_base*$descuento;

              break;

            case 2:

              $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
              
              break;

            case 3:

              $producto->precio_oferta=$precio[$producto->id]['precio'];
              
              break;
            
            default:
            
             $producto->precio_oferta=$producto->precio_base*$descuento;
              # code...
              break;
          }

        }else{

          $producto->precio_oferta=$producto->precio_base*$descuento;

        }


       }else{

       $producto->precio_oferta=$producto->precio_base*$descuento;


       }


       $prods[]=$producto;

      }


        return view('admin.xml.index', compact('roles', 'prods', 'almacen', 'inventario', 'check', 'rolxml'));
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
                              <i class='livicon' data-name='gears' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Agregar Productos'></i>
                      </a>


                      <a href='".secure_url('admin/almacenes/'.$row->id.'/upload')."'>
                              <i class='livicon' data-name='arrow-circle-up' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Agregar Productos'></i>
                      </a>


                      <!--a href='".secure_url('admin/almacenes/'.$row->id.'/roles')."'>
                              <i class='livicon' data-name='users' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Empresa'></i>
                      </a-->
                      

              <a href='".secure_url('admin/almacenes/'.$row->id.'/edit')."'>
                              <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Empresa'></i>
                      </a>  
                      <a href='".secure_url('admin/almacenes/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'> <i class='livicon' data-name='remove-alt' data-size='18'
                        data-loop='true' data-c='#f56954' data-hc='#f56954' title='Eliminar'></i>

                      </a>";


               $data[]= array(
                 $row->id, 
                 $row->nombre_almacen, 
                 $row->descripcion_almacen,
                 $estatus, 
                 $actions
              );

          }

          return json_encode( array('data' => $data ));
          
      }
 
    public function create()
    {

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
      

        // Show the page
        return view ('admin.almacenes.create', compact('almacen', 'states', 'cities'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
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

        $input=$request->all();

        //dd('store');
        
        $user_id = Sentinel::getUser()->id;

        AlpXml::where('estado_registro', '=', '1')->delete();

       
        foreach ($input as $key => $value) {
          

          if (substr($key, 0, 2)=='p_') {

            #echo $key.':'.$value.'<br>';

            $par=explode('p_', $key);

            $data = array(
              'id_producto' => $par[1], 
              'id_rol' => $request->id_rol, 
              'id_user' => $user->id, 
            );

           // dd($data);

            AlpXml::create($data);
            
          }

        }
       

        

        return redirect('admin/xml')->withInput()->with('success', trans('Se ha actualizado satisfactoriamente'));

         

    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
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
       
       $almacen = AlpAlmacenes::where('id', $id)->first();

       $states=State::where('config_states.country_id', '47')->get();

       $city=City::where('id', $almacen->id_city)->first();

      $cities=City::where('state_id', $city->state_id)->get();


        return view('admin.almacenes.edit', compact('almacen', 'cities', 'states', 'city'));
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
                'id_city' => $request->city_id
                );


       $almacen = AlpAlmacenes::find($id);
    
        $almacen->update($data);

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

            $confirm_route = route('admin.almacenes.delete', ['id' => $empresas->id]);

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
            return Redirect::route('admin.almacenes.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.almacenes.index')->with('error', trans('Error al eliminar el registro'));
        }
    }



     public function gestionar($id)
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



        return view('admin.almacenes.gestionar', compact('almacen', 'productos', 'check', 'inventario'));
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
       
        return Redirect::route('admin.almacenes.index')->with('success', trans('Se ha creado satisfactoriamente'));
    }




     public function roles($id)
    {
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

        return view('admin.almacenes.roles', compact('almacen', 'roles', 'check'));
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
       
        return Redirect::route('admin.almacenes.index')->with('success', trans('Se ha creado satisfactoriamente'));
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
              ->get();

              foreach ($salidas as $row) {
                
                //$inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;


              if (isset($inv2[$row->id_producto][$row->id_almacen])) {
                  # code...

                   $inv2[$row->id_producto][$row->id_almacen]= $inv2[$row->id_producto][$row->id_almacen]-$row->cantidad_total;


                }else{


                   $inv2[$row->id_producto][$row->id_almacen]= 0-$row->cantidad_total;
                }

            }

           // dd($inv2);

            return $inv2;
      
    }











     public function upload($id)
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
       

       $almacen = AlpAlmacenes::where('id', $id)->first();

       $almacenes = AlpAlmacenes::get();


        return view('admin.almacenes.upload', compact('almacen', 'almacenes'));
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

        //dd($id);

        //dd($input);

         $archivo = $request->file('file_update');

        //$porciones = explode("_", $request->cities);

        \Session::put('almacen', $id);
        
        \Session::put('inventario', $this->inventario());

        \Session::put('cities', $request->cities);

        Excel::import(new AlmacenImport, $archivo);
        

       
       
        return Redirect::route('admin.almacenes.index')->with('success', trans('Se ha creado satisfactoriamente'));
    }











}