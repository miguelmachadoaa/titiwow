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

use App\Models\AlpTicket;
use App\Models\AlpComentario;
use App\Models\AlpDepartamento;
use App\Models\AlpUrgencia;
use App\Models\AlpDepartamentoUsuario;

use App\User;
use App\State;
use App\City;
use App\Barrio;

use App\Models\AlpAlmacenesUser;
use App\Http\Requests\TicketRequest;
use App\Http\Requests\ComentarioRequest;
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
use File;
use Hash;



class AlpTicketController extends JoshController
{

   
    /**
     * Funcion Index
     * Descrioción: Muestra el index del listado de almacenes del admin
     * 
     * Variables:
     * $almacenes = contiene el resultado de la consulta de almacenes.
     * activity() = Guarda la actividad del usuario en el activity log.
     * Sentinel = Chequea los datos de la sesión del usuario.
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

        $tickets = AlpTicket::all();

        // Show the page
        return view('admin.ticket.index', compact('tickets'));
    }

    public function data()
    {

       

       
        $tickets = AlpTicket::select('alp_ticket.*', 'alp_departamento.nombre_departamento as nombre_departamento', 'alp_urgencia.nombre_urgencia as nombre_urgencia', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
        ->join('alp_departamento', 'alp_ticket.departamento', '=', 'alp_departamento.id')
        ->join('alp_urgencia', 'alp_ticket.urgencia', '=', 'alp_urgencia.id')
        ->join('users', 'alp_ticket.id_user', '=', 'users.id')
        ->get();
         
        $data = array();

        foreach($tickets as $row){

          if ($row->estado_registro=='1') {

             $estatus=" <div class='ticket_".$row->id."'>
             <button data-url='".secure_url('admin/ticket/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='0' class='btn btn-xs btn-success estatus'>Abierto</button>
            </div>";

          }else{

                        $estatus="<div class='estaticket_tus_".$row->id."'>
            <button data-url='".secure_url('admin/ticket/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='1' class='btn btn-xs btn-success estatus'>Cerrado</button>
             </div>";

           }

        $actions = " 
              
                        <a href='".secure_url('admin/ticket/'.$row->id.'')."'>
                              <i class='livicon' data-name='eye-open' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Datos del almacen'></i>
                      </a>  

                      <a href='".secure_url('admin/ticket/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'> <i class='livicon' data-name='remove-alt' data-size='18'
                        data-loop='true' data-c='#f56954' data-hc='#f56954' title='Eliminar'></i>

                      </a>";

                     

               $data[]= array(
                 $row->id, 
                 $row->nombre_departamento, 
                 $row->nombre_urgencia,
                 $row->origen, 
                 $row->titulo_ticket, 
                 $row->created_at->format('d-m-Y h:i:s'), 
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


        $departamentos=AlpDepartamento::get();

        $urgencia=AlpUrgencia::get();

        #dd($departamentos);

        // Show the page
        return view ('admin.ticket.create', compact('departamentos', 'urgencia', ));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(TicketRequest $request)
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

        $input=$request->all();


        $archivo='';


      if ($request->archivo != null) {

        $file = $request->file('archivo');

        $extension = $file->extension()?: 'jpg';

        $picture = str_random(10) . '.' . $extension; 

        $file1 = $file->getClientOriginalName();

        $archivo = $picture;

          $destinationPath = public_path('/uploads/ticket/');   

        $file->move($destinationPath,$archivo);
        
      }

        $data = array(
            'departamento' => $request->departamento, 
            'urgencia' => $request->urgencia, 
            'titulo_ticket' => $request->titulo_ticket, 
            'texto_ticket' => $request->texto_ticket, 
            'archivo' => $archivo, 
            'origen' => 'Administrador', 
            'id_user' =>$user_id
        );
         
        $ticket=AlpTicket::create($data);



       

        if ($ticket->id) {

            return redirect('admin/ticket')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/ticket')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $ticket = AlpTicket::where('id', $id)->first();

      
      $departamentos=AlpDepartamento::get();

        $urgencia=AlpUrgencia::get();


        return view('admin.ticket.edit', compact('ticket', 'departamentos', 'urgencia'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(TicketRequest $request, $id)
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

        $ticket = AlpTicket::where('id', $id)->first();

         $archivo=$ticket->archivo;


      if ($request->archivo != null) {

        $file = $request->file('archivo');

        $extension = $file->extension()?: 'jpg';

        $picture = str_random(10) . '.' . $extension; 

        $file1 = $file->getClientOriginalName();

        $archivo = $picture;

          $destinationPath = public_path('/uploads/ticket/');   

        $file->move($destinationPath,$archivo);
        
      }

                $data = array(
            'departamento' => $request->departamento, 
            'urgencia' => $request->urgencia, 
            'titulo_ticket' => $request->titulo_ticket, 
            'texto_ticket' => $request->texto_ticket, 
            'archivo' => $archivo, 
            'id_user' =>$user->id
        );
         
        $ticket->update($data);


       

        if ($ticket->id) {

            return redirect('admin/ticket')->withInput()->with('success', trans('Se ha actualizado el Registro'));

        } else {
            return Redirect::route('admin/ticket')->withInput()->with('error', trans('Ha ocrrrido un error al actualizar el registro'));
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

            $confirm_route = route('admin.ticket.delete', ['id' => $empresas->id]);

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
            return Redirect::route('admin.ticket.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.ticket.index')->with('error', trans('Error al eliminar el registro'));
        }
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


        return view('admin.ticket.upload', compact('almacen', 'almacenes'));
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
        

       
       
        return Redirect::route('admin.ticket.index')->with('success', trans('Se ha creado satisfactoriamente'));
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


         $ticket = AlpTicket::select('alp_ticket.*', 'alp_departamento.nombre_departamento as nombre_departamento', 'alp_urgencia.nombre_urgencia as nombre_urgencia', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email', 'users.pic as pic')
        ->join('alp_departamento', 'alp_ticket.departamento', '=', 'alp_departamento.id')
        ->join('alp_urgencia', 'alp_ticket.urgencia', '=', 'alp_urgencia.id')
        ->join('users', 'alp_ticket.id_user', '=', 'users.id')
        ->where('alp_ticket.id', '=', $id)
        ->first();

        $comentarios=AlpComentario::select('alp_comentario.*',  'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
         ->join('users', 'alp_comentario.id_user', '=', 'users.id')
        ->where('alp_comentario.id_ticket', $id)->get();


        return view('admin.ticket.show', compact('ticket', 'comentarios'));
    }


     public function postcomentario(ComentarioRequest $request, $id)
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

        $ticket = AlpTicket::where('id', $id)->first();

         $archivo='';


      if ($request->archivo != null) {

        $file = $request->file('archivo');

        $extension = $file->extension()?: 'jpg';

        $picture = str_random(10) . '.' . $extension; 

        $file1 = $file->getClientOriginalName();

        $archivo = $picture;

          $destinationPath = public_path('/uploads/ticket/');   

        $file->move($destinationPath,$archivo);
        
      }

                $data = array(
            'id_ticket' => $id, 
            'titulo_ticket' => $request->titulo_ticket, 
            'texto_ticket' => $request->texto_ticket, 
            'archivo' => $archivo, 
            'id_user' =>$user->id
        );
         
        $comentario=AlpComentario::create($data);


       

        if ($comentario->id) {

            return redirect('admin/ticket/'.$id)->withInput()->with('success', trans('Se ha creado el Registro'));

        } else {
            return Redirect::route('admin/ticket/'.$id)->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }



      





}