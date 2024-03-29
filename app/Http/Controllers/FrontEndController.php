<?php

namespace App\Http\Controllers;

use Activation;

use App\Http\Requests\FrontendRequest;

use App\Http\Requests\PasswordResetRequest;

use App\Http\Requests\UserRequest;

use App\Http\Requests\PqrRequest;

use App\Mail\Contact;

use App\Mail\ContactUser;

use App\Mail\ForgotPassword;

use App\Mail\Register;

use App\Models\AlpClientes;

use App\Models\AlpClientesEmbajador;

use App\Models\AlpCategorias;

use App\Models\AlpEmpresas;

use App\Models\AlpPrecioGrupo;

use App\Models\AlpProductos;

use App\Models\AlpTDocumento;

use App\Models\AlpSliders;

use App\Models\AlpEstructuraAddress;

use App\Models\AlpConfiguracion;

use App\Models\AlpDirecciones;

use App\Models\AlpCodAlpinistas;

use App\Models\AlpInventario;

use App\Models\AlpOrdenesDescuento;

use App\Models\AlpCombosProductos;

use App\Models\AlpClientesHistory;

use App\Models\AlpAlmacenes;

use App\Models\AlpAlmacenRol;

use App\Models\AlpAlmacenProducto;

use App\Models\AlpAlmacenDespacho;

use App\Models\AlpRolenvio;

use App\Models\AlpDetalles;

use App\Models\AlpOrdenes;

use App\Models\AlpEnvios;

use App\Models\AlpEnviosEstatus;

use App\Models\AlpEnviosHistory;

use App\Models\AlpXml;
use App\Models\AlpTicket;
use App\Models\AlpTicketHistory;
use App\Models\AlpDepartamento;


use App\User;

use App\State;

use App\City;

use App\Barrio;

use App\RoleUser;

use App\Roles;

use App\Models\AlpMenuDetalle;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;

use Cartalyst\Sentinel\Checkpoints\ThrottlingException;

use File;

use Hash;

use Illuminate\Http\Request;

use Mail;

use Redirect;

use Reminder;

use Sentinel;

use URL;

use Validator;

use View;

use DB; 

use Exception;

use Carbon\Carbon;

use Intervention\Image\Facades\Image;




class FrontEndController extends JoshController
{

 

  public function getCompramas(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/getCompramas ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/getCompramas');
        }

      $content = $request->getContent();

      $input = json_decode($content, true);
      
     

    $r="false";

   
 
    if (isset($input[0]['ordenId'])) {

    $orden=AlpOrdenes::where('referencia', $input[0]['ordenId'])->first();
    
    

    if (isset($orden->id)) {

      $user=User::where('id', $orden->id_cliente)->first();

      if (isset($user->id)) {

      }

      $envio=AlpEnvios::where('id_orden', $orden->id)->first();

      if (isset($envio->id)) {

        $status=AlpEnviosEstatus::where('codigo', $input[0]['estado'])->first();

        $user=User::where('id', $orden->id_user)->first();

        if (isset($status->id)) {

          $data_histroy = array(
            'id_envio' => $envio->id, 
            'estatus_envio' => $status->id, 
            'nota' => 'Actualizado por compramas', 
            'id_user' => '1', 
            'json' => json_encode($input) 

          );

          AlpEnviosHistory::create($data_histroy);

          $data_envio = array('estatus' => $status->id);

          $envio->update($data_envio);

          $r="true";

           Mail::to($user->email)->send(new \App\Mail\NotificacionEnvio($user, $orden, $envio, $status, $input[0] ));

        }

      }

    }

    }


    return response(json_encode($r), 200) ->header('Content-Type', 'application/json');

  }


  public function getCompramasInventarioAlmacen(Request $request, $id)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/getCompramasInventarioAlmacen ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/getCompramasInventarioAlmacen');
        }

      $content = $request->getContent();

      $datos = json_decode($content, true);

       activity()->withProperties($datos)->log('FrontEndController/getCompramasInventarioAlmacen Data Recibida ');

    $r="false";

    $resp = array();

       $inventario=$this->inventarioAlmacen($id);

       $almacen=$id;

       $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $data_almacen = array();

        $data_inventario = array();

        $ids_inventario = array();

        $ids_almacen = array();

       if (count($datos)) {

            foreach ($datos as $dato ) {

            # activity()->withProperties($dato)->log('FrontEndController/getCompramasInventario  2');

           # activity()->withProperties($dato['stock'])->log('FrontEndController/getCompramasInventario 2.1');

            $p=AlpProductos::where('referencia_producto', $dato['sku'])->first();

              if ($dato['stock']>=0) {

                  if (isset($p->id)) {

                        $r='true';

                        $resp[$dato['sku']]='true';

                        $data = array(
                          'id_almacen' => $almacen, 
                           'id_producto' => $p->id, 
                           'created_at' => $date,
                          'id_user' => 1 
                        );

                        $data_almacen[]=$data;

                        $ids_almacen[]=$p->id;

                        $ids_inventario[]=$p->id;

                        $data_inventario_nuevo = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'cantidad' => $dato['stock'], 
                            'operacion' => 1, 
                            'notas' => 'Actualización de inventario por api compramas', 
                            'created_at' => $date, 
                            'id_user' => 1 
                        );

                        $data_inventario[]=$data_inventario_nuevo;

                  }else{

                    $resp[$dato['sku']]='false';

                  }

                }else{

                  if (isset($p->id)) {

                    $resp[$dato['sku']]='true';

                     $ap=AlpAlmacenProducto::where('id_almacen', $almacen)->where('id_producto', $p->id)->first();

                     if (isset($ap->id)) {

                        $ids_inventario[]=$p->id;

                        $ids_almacen[]=$p->id;

                     }

                  }

                }

            } //end foreach datos

            AlpInventario::whereIn('id_producto', $ids_inventario)->where('id_almacen', $almacen)->delete();

            AlpAlmacenProducto::whereIn('id_producto', $ids_almacen)->where('id_almacen', $almacen)->delete();

            AlpAlmacenProducto::insert($data_almacen);

            AlpInventario::insert($data_inventario);

       } //(end if hay resspuessta)

      activity()->withProperties($resp)->log('FrontEndController/getCompramasInventario Data Respuesta ');

      return response(json_encode($resp), 200) ->header('Content-Type', 'application/json');


  }


  


  



  public function getCompramasInventario(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/getCompramasInventario ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/getCompramasInventario');
        }

      $content = $request->getContent();

      $datos = json_decode($content, true);

       activity()->withProperties($datos)->log('FrontEndController/getCompramasInventario Data Recibida ');

    $r="false";

    $resp = array();

       $inventario=$this->inventario();

       $almacen=1;

       $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $data_almacen = array();

        $data_inventario = array();

        $ids_inventario = array();

        $ids_almacen = array();

       if (count($datos)) {

            foreach ($datos as $dato ) {

            # activity()->withProperties($dato)->log('FrontEndController/getCompramasInventario  2');

           # activity()->withProperties($dato['stock'])->log('FrontEndController/getCompramasInventario 2.1');

            $p=AlpProductos::where('referencia_producto', $dato['sku'])->first();

              if ($dato['stock']>=0) {

                  if (isset($p->id)) {

                        $r='true';

                        $resp[$dato['sku']]='true';

                        $data = array(
                          'id_almacen' => $almacen, 
                           'id_producto' => $p->id, 
                           'created_at' => $date,
                          'id_user' => 1 
                        );

                        $data_almacen[]=$data;

                        $ids_almacen[]=$p->id;

                        $ids_inventario[]=$p->id;

                        $data_inventario_nuevo = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'cantidad' => $dato['stock'], 
                            'operacion' => 1, 
                            'notas' => 'Actualización de inventario por api compramas', 
                            'created_at' => $date, 
                            'id_user' => 1 
                        );

                        $data_inventario[]=$data_inventario_nuevo;

                  }else{

                    $resp[$dato['sku']]='false';

                  }

                }else{

                  if (isset($p->id)) {

                    $resp[$dato['sku']]='true';

                     $ap=AlpAlmacenProducto::where('id_almacen', $almacen)->where('id_producto', $p->id)->first();

                     if (isset($ap->id)) {

                        $ids_inventario[]=$p->id;

                        $ids_almacen[]=$p->id;

                     }

                  }

                }

            } //end foreach datos

            AlpInventario::whereIn('id_producto', $ids_inventario)->where('id_almacen', $almacen)->delete();

            AlpAlmacenProducto::whereIn('id_producto', $ids_almacen)->where('id_almacen', $almacen)->delete();

            AlpAlmacenProducto::insert($data_almacen);

            AlpInventario::insert($data_inventario);

       } //(end if hay resspuessta)

      activity()->withProperties($resp)->log('FrontEndController/getCompramasInventario Data Respuesta ');

      return response(json_encode($resp), 200) ->header('Content-Type', 'application/json');


  }



    public function getXml()
    {

        $id='1';

      $productos=AlpProductos::select('alp_productos.*', 'alp_marcas.nombre_marca as nombre_marca')
      ->join('alp_marcas', 'alp_productos.id_marca', '=','alp_marcas.id')

     // ->join('alp_xml', 'alp_productos.id', '=','alp_xml.id_producto')
      ->join('alp_almacen_producto', 'alp_productos.id', '=','alp_almacen_producto.id_producto')
      ->where('alp_productos.estado_registro','=',1)
      ->where('alp_almacen_producto.id_almacen','=','1')
      ->whereNull('alp_almacen_producto.deleted_at')
      //->whereNull('alp_xml.deleted_at')
      ->whereNull('alp_productos.deleted_at')
      ->groupBy('alp_productos.id')
      ->get();


      $cs1=AlpXml::first();


          $precio = array();

         foreach ($productos as  $row) {

          if (isset($cs1->id_rol)) {

            $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $cs1->id_rol)->first();

            if (isset($pregiogrupo->id)) {

                $precio[$row->id]['precio']=$pregiogrupo->precio;

                $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                $precio[$row->id]['pum']=$pregiogrupo->pum;


            }

          }


        }

        $descuento=1;

        $prods = array( );

        foreach ($productos as $producto) {

          if ($descuento=='1') {

           if (isset($precio[$producto->id])) {

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


      $inventario=$this->inventario();

      return view('frontend.xml', compact('prods', 'inventario', 'id'));

    }


    

    public function getXmlAlmacen($id)
    {

      $productos=AlpProductos::select('alp_productos.*', 'alp_marcas.nombre_marca as nombre_marca')
      ->join('alp_marcas', 'alp_productos.id_marca', '=','alp_marcas.id')
     // ->join('alp_xml', 'alp_productos.id', '=','alp_xml.id_producto')
      ->join('alp_almacen_producto', 'alp_productos.id', '=','alp_almacen_producto.id_producto')
      ->where('alp_productos.estado_registro','=',1)
      ->where('alp_almacen_producto.id_almacen', '=', $id)
      ->whereNull('alp_almacen_producto.deleted_at')
      //->whereNull('alp_xml.deleted_at')
      ->whereNull('alp_productos.deleted_at')
      ->groupBy('alp_productos.id')
      ->get();

      #dd($productos);


      $cs1=AlpXml::first();

          $precio = array();

         foreach ($productos as  $row) {

          if (isset($cs1->id_rol)) {

            $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $cs1->id_rol)->first();

            if (isset($pregiogrupo->id)) {

                $precio[$row->id]['precio']=$pregiogrupo->precio;

                $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                $precio[$row->id]['pum']=$pregiogrupo->pum;


            }

          }


        }

        $descuento=1;

        $prods = array( );

        foreach ($productos as $producto) {

          if ($descuento=='1') {

           if (isset($precio[$producto->id])) {

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


      $inventario=$this->inventario();

      return view('frontend.xml', compact('prods', 'inventario', 'id'));

    }




    public function confirmarcorreo($token)
    {

        $user=User::where('token', $token)->first();

        if (isset($user->id)) {


          activity($user->full_name)
          ->performedOn($user)
          ->causedBy($user)
          ->withProperties($user)->log('FrontEndController/confirmarcorreo');

          $data_user = array(
            'confirma_email' => 1
            //'token'=>md5(time()) 
          );

          $user->update($data_user);

          return view('frontend.confirmacorreo', compact('user'));
          # code...
        }else{

          abort('404');

        }

    }


    public function reenviarcorreo($token)
    {

       $user = Sentinel::getUser();

        if (isset($user->id)) {

          Mail::to($user->email)->send(new \App\Mail\NotificacionCorreo($user));

          Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionCorreo($user));

          return view('frontend.reenviarcorreo', compact('user'));
          # code...
        }else{

          abort('404');

        }

    }



    public function getPqr()

    {

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $cart= \Session::get('cart');

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

              if (isset($row->id)) {

                $total=$total+($row->cantidad*$row->precio_oferta);

              }

            }

        }



        return view('frontend.pqr', compact('t_documento', 'cart', 'total'));



    }


    public function postPQR(PqrRequest $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->all())
            ->log('FrontEndController/postPQR ');

        }else{

          activity()->withProperties($request->all())

                        ->log('FrontEndController/postPQR');

        }

        $archivo='0';

      $input=$request->all();

      $input['nombre_pqr']=strip_tags($input['nombre_pqr']);
      $input['apellido_pqr']=strip_tags($input['apellido_pqr']);
      $input['tdocume_pqr']=strip_tags($input['tdocume_pqr']);
      $input['identificacion_pqr']=strip_tags($input['identificacion_pqr']);
      $input['celular_pqr']=strip_tags($input['celular_pqr']);
      $input['email_pqr']=strip_tags($input['email_pqr']);
      $input['pais_pqr']=strip_tags($input['pais_pqr']);
      $input['ciudad_pqr']=strip_tags($input['ciudad_pqr']);
      $input['tipo_pqr']=strip_tags($input['tipo_pqr']);
      $input['mensaje_pqr']=strip_tags($input['mensaje_pqr']);
      

      if ($request->file_update != null) {

        $file = $request->file('file_update');

        $extension = $file->extension()?: 'jpg';

        $picture = str_random(10) . '.' . $extension; 

        $file1 = $file->getClientOriginalName();

        $archivo = $picture;

        $destinationPathDestino = public_path('/uploads/ticket/');   

        #Image::make($file)->save($destinationPath); 

        $destinationPath = public_path('/uploads/pqr/');   

        $file->move($destinationPath,$archivo);

        if (!copy($destinationPath.$picture, $destinationPathDestino.$picture)) {
          echo "Error al copiar $fichero...\n";
        }

      
        
      }

      $configuracion=AlpConfiguracion::where('id', '1')->first();


      if($input['tipo_pqr']=='Felicitaciones'){ }else{


        $mensaje='';
        $mensaje=$mensaje.' <b>Nombre:</b>'. $input['nombre_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Apellido:</b>'. $input['apellido_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Tipo de Documento:</b>'. $input['tdocume_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Identificación:</b>'. $input['identificacion_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Email:</b>'. $input['email_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Celular:</b>'. $input['celular_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Pais:</b>'. $input['pais_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Ciudad:</b>'. $input['ciudad_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Mensaje:</b>'. $input['mensaje_pqr'].'<br>';
        $mensaje=$mensaje.' <b>Términos:</b>'. $input['habeas_cliente'].'<br>';


        $data = array(
          'departamento' => '1', 
          'urgencia' => '1', 
          'caso' => '15',
          'titulo_ticket' => 'PQR '.$input['nombre_pqr'].' '.$input['apellido_pqr'], 
          'texto_ticket' => $mensaje, 
          'orden' =>'', 
          'archivo' => $archivo, 
          'origen' => 'Administrador', 
          'id_user' =>'1'
        );
     
        $ticket=AlpTicket::create($data);

        $arrayhistory = array(
              'id_ticket' => $ticket->id,
              'id_status' => '1',
              'notas' => 'Ticket creado',
              'json' => json_encode($ticket),
              'id_user' => '1',
            );

         AlpTicketHistory::create($arrayhistory);

          $departamento=AlpDepartamento::where('id', '1')->first();

        $correos = explode(";", $departamento->correos);

        foreach ($correos as $key => $value) {

          try {

            Mail::to(trim($value))->send(new \App\Mail\NotificacionTicket($ticket));

          } catch (\Exception $e) {

            activity()->withProperties(1)
                        ->log('error envio de correo ticket controller 302');

          }

          #  Mail::to($ud->email)->send(new \App\Mail\NotificacionTicket($ticket));

        }



      }


      


       try {

            Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\PQR($input, $archivo));
            
            Mail::to('servicioalcliente.asesor16@alpina.com')->send(new \App\Mail\PQR($input, $archivo));
            Mail::to('servicioalcliente.coordinador@alpina.com')->send(new \App\Mail\PQR($input, $archivo));
            Mail::to('rumi.torres@alpina.com')->send(new \App\Mail\PQR($input, $archivo));

            return redirect('pqr')->with('aviso', 'Su correo se ha enviado satisfactoriamente');

          } catch (Swift_RfcComplianceException $e) {
            
            return redirect('pqr')->with('aviso', 'Ha ocurrido un problema intente nuevamente ');

          }
    }



    public function getContacto()

    {

        // Is the user logged in?

        

        $url=secure_url('/contacto');



        return view('frontend.contacto', compact('url'));



    }



    

    public function home()
    {

       $cart= \Session::get('cart');

       if (isset($cart['id_forma_pago']) || isset($cart['id_forma_envio']) || isset($cart['id_cliente']) || isset($cart['id_almacen']) || isset($cart['id_direccion']) || isset($cart['inventario']) ) {

          $cart= \Session::forget('cart');

          \Session::put('cart', array());

       }


      $id_almacen=$this->getAlmacen();

      #dd($id_almacen);

      $m=AlpMenuDetalle::menus(1);

        $rol=9;

        $descuento='1'; 

        $clientIP = \Request::getClientIp(true);

        $precio = array();

        $configuracion=AlpConfiguracion::where('id', '1')->first();

        $categorias = DB::table('alp_categorias')->select('alp_categorias.*')->where('destacado','=', 1)->where('alp_categorias.estado_registro','=',1)->whereNull('alp_categorias.deleted_at')->orderBy('order', 'asc')->limit(9)->get();
        
        $productos = DB::table('alp_productos')->select('alp_productos.*','alp_marcas.order as order', 'alp_marcas.nombre_marca as nombre_marca', 'alp_categorias.nombre_categoria as nombre_categoria')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
       ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->join('alp_categorias','alp_productos.id_categoria_default' , '=', 'alp_categorias.id')
       ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
        ->join('alp_destacados_producto', 'alp_productos.id', '=', 'alp_destacados_producto.id_producto')
        ->where('alp_destacados_producto.id_almacen', '=', $id_almacen)
        ->where('alp_destacados_producto.id_grupo_destacado', '=', '1')
        ->whereNull('alp_destacados_producto.deleted_at')
        ->whereNull('alp_almacen_producto.deleted_at')
        ->whereNull('alp_productos.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_productos.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->get();

        //dd($productos);


        $marcas = DB::table('alp_marcas')->select('alp_marcas.*')->where('destacado','=', 1)->where('alp_marcas.estado_registro','=',1)->whereNull('alp_marcas.deleted_at')->orderBy('order', 'asc')->limit(12)->get();

        $ciudad= \Session::get('ciudad');


        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $user=Sentinel::getUser();

            $role=RoleUser::where('user_id', $user_id)->first();

            $rol=$role->role_id;

            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();


            if (isset($d->id)) {

            }else{



                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();

            }



            if (isset($d->id)) {

              $ciudad=$d->city_id;

            }

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {

                    $role->role_id='E'.$role->role_id.'';

                }

            }

            if ($role->role_id) {

                foreach ($productos as  $row) {

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {

                        $precio[$row->id]['precio']=$pregiogrupo->precio;

                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }else{

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', '=','62')->first();

                      if (isset($pregiogrupo->id)) {

                          $precio[$row->id]['precio']=$pregiogrupo->precio;

                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                          $precio[$row->id]['pum']=$pregiogrupo->pum;

                      }

                    }

                }

            }

        }else{

          $role = array( );

            $r='9';

                foreach ($productos as  $row) {

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {

                        $precio[$row->id]['precio']=$pregiogrupo->precio;

                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }else{

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', '=','62')->first();

                      if (isset($pregiogrupo->id)) {

                          $precio[$row->id]['precio']=$pregiogrupo->precio;

                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                          $precio[$row->id]['pum']=$pregiogrupo->pum;

                      }

                    }

                }

        }

        $prods = array( );

        foreach ($productos as $producto) {

      if ($descuento=='1') {

        if (isset($precio[$producto->id])) {

          switch ($precio[$producto->id]['operacion']) {

            case 1:

              $producto->precio_oferta=$producto->precio_base*$descuento;

              $producto->pum=$precio[$producto->id]['pum'];


              break;

            case 2:

              $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));

              $producto->pum=$precio[$producto->id]['pum'];

              break;

            case 3:

              $producto->precio_oferta=$precio[$producto->id]['precio'];

              $producto->pum=$precio[$producto->id]['pum'];

              break;

            default:

             $producto->precio_oferta=$producto->precio_base*$descuento;

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

       $cart= \Session::get('cart');

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

              if (isset($row->id)) {

                $total=$total+($row->cantidad*$row->precio_oferta);

              }

            }

        }

        $inventario=$this->inventario();

        $combos=$this->combos();

        $role=Roles::where('id', $rol)->first();

       $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

       $url=secure_url('/');


        $sliders=AlpSliders::select('alp_slider.*')
        ->join('alp_almacen_slider','alp_slider.id', '=', 'alp_almacen_slider.id_slider')
        ->where('alp_almacen_slider.id_almacen', '=', $id_almacen)
        ->where('alp_slider.grupo_slider', '=', 1)
        ->whereNull('alp_slider.deleted_at')
        ->whereNull('alp_almacen_slider.deleted_at')
        ->orderBy("order")->get();

        $datalayer_slider=array();
        $datalayer_slider_id=array();

        foreach($sliders as $slider){

            $sli = array(
              'id' => $slider->id, 
              'name' => $slider->nombre_slider, 
              'creative' => 'home', 
              'position' => $slider->order, 
            );

            $datalayer_slider[]=$sli;
            $datalayer_slider_id[$slider->id]=$sli;

        }


      $cart= \Session::get('cart');


       if (isset($cart['id_forma_pago']) || isset($cart['id_forma_envio']) || isset($cart['id_cliente']) || isset($cart['id_almacen']) || isset($cart['id_direccion']) || isset($cart['inventario']) ) {

          $cart= \Session::forget('cart');

          $cart = array();

       }


        return view('index',compact('categorias','productos','marcas','descuento','precio', 'cart', 'total','prods','sliders','configuracion','inventario', 'combos', 'role', 'almacen','url', 'datalayer_slider', 'datalayer_slider_id'));


    }

    public function qlub()
    {
       $cart= \Session::get('cart');
       if (isset($cart['id_forma_pago']) || isset($cart['id_forma_envio']) || isset($cart['id_cliente']) || isset($cart['id_almacen']) || isset($cart['id_direccion']) || isset($cart['inventario']) ) {

          $cart= \Session::forget('cart');
          \Session::put('cart', array());
       }
      $id_almacen=$this->getAlmacen();
      $m=AlpMenuDetalle::menus(1);
        $rol=9;
        $descuento='1'; 
        $clientIP = \Request::getClientIp(true);
        $precio = array();
        $configuracion=AlpConfiguracion::where('id', '1')->first();
        $categorias = DB::table('alp_categorias')->select('alp_categorias.*')->where('destacado','=', 1)->where('alp_categorias.estado_registro','=',1)->whereNull('alp_categorias.deleted_at')->orderBy('order', 'asc')->limit(9)->get();
        
        $productos = DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order', 'alp_marcas.nombre_marca', 'alp_categorias.nombre_categoria')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
        ->join('alp_categorias','alp_productos.id_categoria_default' , '=', 'alp_categorias.id')
        ->join('alp_destacados_producto', 'alp_productos.id', '=', 'alp_destacados_producto.id_producto')
        ->where('alp_destacados_producto.id_grupo_destacado', '=', '2')
        ->where('alp_destacados_producto.id_almacen', '=', $id_almacen)
        ->whereNull('alp_destacados_producto.deleted_at')
        ->whereNull('alp_productos.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_productos.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->get();
        
        //dd($productos);
        $marcas = DB::table('alp_marcas')->select('alp_marcas.*')->where('destacado','=', 1)->where('alp_marcas.estado_registro','=',1)->whereNull('alp_marcas.deleted_at')->orderBy('order', 'asc')->limit(12)->get();

        $ciudad= \Session::get('ciudad');

       // dd($ciudad);
        if (Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user=Sentinel::getUser();
            $role=RoleUser::where('user_id', $user_id)->first();
            $rol=$role->role_id;
            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')

              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')

              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')

              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')

              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')

              ->where('alp_direcciones.id_client', $user_id)

              ->where('alp_direcciones.default_address', '=', '1')

              ->first();

            if (isset($d->id)) {

            }else{

                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')

                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')

                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')

                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')

                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')

                ->where('alp_direcciones.id_client', $user_id)

                ->first();

            }

            if (isset($d->id)) {

              $ciudad=$d->city_id;

            }

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    $role->role_id='E'.$role->role_id.'';

                }

            }

            if ($role->role_id) {

                foreach ($productos as  $row) {

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {

                        $precio[$row->id]['precio']=$pregiogrupo->precio;

                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }else{

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', '=','62')->first();

                      if (isset($pregiogrupo->id)) {

                          $precio[$row->id]['precio']=$pregiogrupo->precio;

                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                          $precio[$row->id]['pum']=$pregiogrupo->pum;

                      }

                    }

                }

            }

        }else{
          $role = array( );
            $r='9';
                foreach ($productos as  $row) {
                  //dd($row);
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {

                        $precio[$row->id]['precio']=$pregiogrupo->precio;

                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }else{

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', '=','62')->first();

                      if (isset($pregiogrupo->id)) {

                      
                          $precio[$row->id]['precio']=$pregiogrupo->precio;

                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                          $precio[$row->id]['pum']=$pregiogrupo->pum;

                      }

                    }
                }
        }

      //  dd($precio);

        $prods = array( );

        foreach ($productos as $producto) {

      if ($descuento=='1') {

        if (isset($precio[$producto->id])) {

          switch ($precio[$producto->id]['operacion']) {

            case 1:

              $producto->precio_oferta=$producto->precio_base*$descuento;

              $producto->pum=$precio[$producto->id]['pum'];

              break;

            case 2:

              $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));

              $producto->pum=$precio[$producto->id]['pum'];

              break;

            case 3:

              $producto->precio_oferta=$precio[$producto->id]['precio'];

              $producto->pum=$precio[$producto->id]['pum'];

              break;

            default:

             $producto->precio_oferta=$producto->precio_base*$descuento;

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

       $cart= \Session::get('cart');

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

              if (isset($row->id)) {  

                $total=$total+($row->cantidad*$row->precio_oferta);

              }

            }

        }


        $inventario=$this->inventario();

        $combos=$this->combos();

        $role=Roles::where('id', $rol)->first();

       // dd($prods);

       // 

       $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

       //dd($inventario);

       $url=secure_url('/');

        $sliders=AlpSliders::select('alp_slider.*')
        ->join('alp_almacen_slider','alp_slider.id', '=', 'alp_almacen_slider.id_slider')
        ->where('alp_almacen_slider.id_almacen', '=', $id_almacen)
        ->where('alp_slider.grupo_slider', '=', 2)
        ->whereNull('alp_slider.deleted_at')
        ->whereNull('alp_almacen_slider.deleted_at')
        ->orderBy("order")->get();

      $cart= \Session::get('cart');

       if (isset($cart['id_forma_pago']) || isset($cart['id_forma_envio']) || isset($cart['id_cliente']) || isset($cart['id_almacen']) || isset($cart['id_direccion']) || isset($cart['inventario']) ) {

          $cart= \Session::forget('cart');

          $cart = array();

       }

        return view('qlub',compact('categorias','productos','marcas','descuento','precio', 'cart', 'total','prods','sliders','configuracion','inventario', 'combos', 'role', 'almacen','url'));
    }



    private $user_activation = false;


    public function getLogin()
    {

        if (Sentinel::check()) {

            return Redirect::route('clientes');

        }

        return view('login');

    }

     public function desactivado()
    {

        return view('desactivado');

    }


    /**
     * Account sign in form processing.
     *
     * @return Redirect
     */

    public function postLogin(Request $request)
    {

     

    # dd($request->all());


            // Try to log the user in

            if ($user=  Sentinel::authenticate(['email'=>$request->email, 'password'=>$request->password], $request->get('remember-me', 0))) {


                //Activity log for login

                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('LoggedIn');

                    $role = DB::table('role_users')
                   ->select('role_users.role_id')
                   ->where('user_id','=', $user->id)
                   ->first();

               if ($role->role_id=='15') {

                 return redirect("admin")->with('success', trans('auth/message.login.success'));

               }

               if ( $role->role_id>'8' || $role->role_id=='13'  || $role->role_id!='15') {

                 $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                  ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                  ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                  ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                  ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                  ->where('alp_direcciones.id_client', $user->id)
                  ->where('alp_direcciones.default_address', '=', '1')
                  ->first();


                  
                  if ($request->back=='0') {

                      return Redirect::route("clientes")->with('success', trans('auth/message.login.success'));

                  }else{

                      return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));

                  }


               }else{

                  #return redirect('admin');

                  return redirect("admin")->with('success', trans('auth/message.login.success'));

               }     

            } else {

                #return 'false';

                return redirect('login')->with('error', 'El Email o Contraseña son Incorrectos.');

            }


       

       # return 'false';

        // Ooops.. something went wrong

        return Redirect::back()->withInput()->withErrors($this->messageBag);

    }


    public function postLogin2(Request $request)
    {


        try {

            // Try to log the user in

            if ($user=  Sentinel::authenticate($request->only('email', 'password'), $request->get('remember-me', 0))) {

                //Activity log for login

                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('LoggedIn');


                $role = DB::table('role_users')
               ->select('role_users.role_id')
               ->where('user_id','=', $user->id)
               ->first();



               if ($role->role_id=='15') {

                 return redirect("admin")->with('success', trans('auth/message.login.success'));

               }


               if ( $role->role_id>'8' || $role->role_id=='13'  || $role->role_id!='15') {

                 $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                  ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                  ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                  ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                  ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                  ->where('alp_direcciones.id_client', $user->id)
                  ->where('alp_direcciones.default_address', '=', '1')
                  ->first();

                  if (isset($d->id)) {

                  }else{

                     return redirect('misdirecciones')->with('error', 'Debes crear una dirección para completar el proceso de compra..');

                  }

                  if ($request->back=='0') {

                      return Redirect::route("clientes")->with('success', trans('auth/message.login.success'));

                  }else{

                      return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));

                  }


               }else{

                  return redirect("admin")->with('success', trans('auth/message.login.success'));

               }     

            } else {

                return redirect('login')->with('error', 'El Email o Contraseña son Incorrectos.');

            }


        } catch (UserNotFoundException $e) {

            $this->messageBag->add('email', trans('auth/message.account_not_found'));

        } catch (NotActivatedException $e) {

            $this->messageBag->add('email', trans('auth/message.account_not_activated'));

        } catch (UserSuspendedException $e) {

            $this->messageBag->add('email', trans('auth/message.account_suspended'));

        } catch (UserBannedException $e) {

            $this->messageBag->add('email', trans('auth/message.account_banned'));

        } catch (ThrottlingException $e) {

            $delay = $e->getDelay();

            $this->messageBag->add('email', trans('auth/message.account_suspended', compact('delay')));

        }



        // Ooops.. something went wrong

        return Redirect::back()->withInput()->withErrors($this->messageBag);

    }







    /**

     * get user details and display

     */

    public function myAccount(User $user)
    {

        $user = Sentinel::getUser();

        if(isset($user->id)){

           $cliente=AlpClientes::where('id_user_client', '=', $user->id)->first();

        $countries = $this->countries;

        $cart= \Session::get('cart');
        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        return view('user_account', compact('user', 'countries', 'cart', 'cliente', 't_documento'));


        }else{

          return redirect('login');


        }

       

    }



    /**

     * update user details and display

     * @param Request $request

     * @param User $user

     * @return Return Redirect

     */

    public function update(User $user, FrontendRequest $request)

    {

        $user = Sentinel::getUser();


        $user->update($request->except('id_type_doc','doc_cliente','pic','password_confirm','marketing_email','telefono_cliente','marketing_sms'));


        if ($password = $request->get('password')) {

            $user->password = Hash::make($password);

        }

        if ($file = $request->file('pic')) {

            $extension = $file->extension()?: 'png';

            $folderName = '/uploads/users/';

            $destinationPath = public_path() . $folderName;

            $safeName = str_random(10) . '.' . $extension;

            $file->move($destinationPath, $safeName);


            if (File::exists(public_path() . $folderName . $user->pic)) {

                File::delete(public_path() . $folderName . $user->pic);

            }

            $user->pic = $safeName;

        }


         $cliente=AlpClientes::where('id_user_client', '=', $user->id)->first();

        if (isset($request->marketing_sms)) {

          $cliente->marketing_sms=1;

        }else{

          $cliente->marketing_sms=0;

        }



        if (isset($request->marketing_email)) {



          $cliente->marketing_email=1;

          

        }else{



          $cliente->marketing_email=0;

        }

        $cliente->telefono_cliente=$request->telefono_cliente;
        $cliente->doc_cliente=$request->doc_cliente;
        $cliente->id_type_doc=$request->id_type_doc;

        $cliente->save();


       $datos360= $this->datos360update($user->id); 







        // Was the user updated?

        if ($user->save()) {

            // Prepare the success message

            $success = trans('users/message.success.update');

            //Activity log for update account

            activity($user->full_name)

                ->performedOn($user)

                ->causedBy($user)

                ->log('User Updated successfully');

            // Redirect to the user page

            return Redirect::route('clientes')->with('success', $success);

        }



        // Prepare the error message

        $error = trans('users/message.error.update');





        // Redirect to the user page

        return Redirect::route('clientes')->withInput()->with('error', $error);





    }



    /**

     * Account Register.

     *

     * @return View

     */

    public function getRegister()
    {


        $configuracion=AlpConfiguracion::where('id', '1')->first();

        if ( $configuracion->registro_publico==0) {

                return view('desactivado');

        }

        $ad=AlpAlmacenDespacho::where('id_state', 0)->first();

        if (isset($ad->id)) {

          $states=State::where('config_states.country_id', '47')->get();

        }else{

           $states = DB::table("config_states")
            ->select('config_states.*')
            ->join('alp_almacen_despacho', 'config_states.id', '=', 'alp_almacen_despacho.id_state')
            ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
            ->where("config_states.country_id",'=', '47')
            ->where("alp_almacenes.estado_registro",'=', '1')
            ->groupBy('config_states.id')
            ->get();


        }

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

        return view('register', compact('states','t_documento','estructura'));

    }




    public function postRegister(UserRequest $request)
    {

         $configuracion=AlpConfiguracion::where('id', '1')->first();
         
         $input=$request->all();

        # dd($input);

         if($configuracion->user_activacion==0){

            $activate=true;
            $masterfi=1;

         }else{

            $activate=false;
            $masterfi=0;

         }

         // $request->email=base64_decode($request->email);
          $request->password=base64_decode($request->password);
          $request->doc_cliente=base64_decode($request->doc_cliente);

          $request->dob=strip_tags($request->dob);
          $request->first_name=strip_tags($request->first_name);
          $request->last_name=strip_tags($request->last_name);
          $request->email=strip_tags($request->email);
          $request->password=strip_tags($request->password);
          $request->doc_cliente=strip_tags($request->doc_cliente);
          $request->cod_alpinista=strip_tags($request->cod_alpinista);

          $u=User::where('email', $request->email)->first();


          if(isset($u->id)){

            return 'falseEmail';

          }


        try {

            if($request->chkalpinista == 1) {

                $codalpin = AlpCodAlpinistas::where('documento_alpi', $request->doc_cliente)->where('codigo_alpi', $request->cod_alpinista)->where('estatus_alpinista',1)->first();

                if ($codalpin) {

                    $data_user = array(
                      'first_name' => $request->first_name, 
                      'last_name' => $request->last_name, 
                      'email' => $request->email, 
                      'dob' => $request->dob, 
                      'password' => $request->password, 
                      'token'=>md5(time())
                    );

                      $user = Sentinel::register($data_user, $activate);

                      $data = array(
                      'id_user_client' => $user->id, 
                      'id_type_doc' => $request->id_type_doc, 
                      'doc_cliente' =>$request->doc_cliente, 
                      'telefono_cliente' => $request->telefono_cliente,
                      'habeas_cliente' => $request->habeas_cliente,
                      'estado_masterfile' =>$masterfi,
                      'cod_alpinista'=> $request->cod_alpinista,
                      'cod_oracle_cliente'=> $codalpin->cod_oracle_cliente,
                      'marketing_email'=> '1',
                      'marketing_sms'=> '1',
                      'id_empresa' =>'0',               
                      'id_embajador' =>'0',               
                      'id_user' =>0,               
                      );

                      $cliente=AlpClientes::create($data);

                      $sialpin = array(
                        'id_usuario_creado' => $user->id, 
                        'estatus_alpinista' => 2    
                     );

                      AlpCodAlpinistas::where('id',$codalpin->id)->update($sialpin);

                      $masterfile = array(
                        'estado_masterfile' => 1 ,
                        'nota' => 'Alpinista Registrado automaticamente'
                      );

                      AlpClientes::where('id',$user->id)->update($masterfile);

                      if ($request->id_barrio=='0') {

                        # code...

                        }else{

                          $b=Barrio::where('id', $request->id_barrio)->first();

                            if (isset($b->id)) {

                              $request->barrio_address=$b->barrio_name;

                            }

                        }


                      


                       $data_c = array(
                            'cod_oracle_cliente' =>$request->telefono_cliente,
                            'estado_masterfile' =>'1'
                        );

                      $cliente->update($data_c);

                      //add user to 'Embajador' group

                      $role = Sentinel::findRoleById(10);

                      $role->users()->attach($user);

                     $activation = Activation::exists($user);

                      if ($activation) {

                        $activation = Activation::create($user);

                          Activation::complete($user, $activation->code);

                      }

                      $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';

                        $roleusuario=RoleUser::where('user_id', $user->id)->first();

                      #  Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida, $roleusuario ));

                      # Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida, $roleusuario ));

                    }else{

                        return 'false1';

                       # return redirect('registro')->with('error', trans('auth/message.failure.error'))->withInput();

                    }

            }else{

              $data_user = array(
                    'first_name' => $request->first_name, 
                    'last_name' => $request->last_name, 
                    'dob' => $request->dob, 
                    'email' => $request->email, 
                    'password' => $request->password, 
                    'token'=>md5(time())
                  );


                  $user = Sentinel::register($data_user, $activate);

                  if ($request->convenio!='') {

                    $empresa=AlpEmpresas::where('convenio', $request->convenio)->first();

                    if (isset($empresa->id)) {

                      # code...

                    }else{

                      return 'false1';

                     # return redirect('registro')->with('error', trans('El Código de Convenio no existe'))->withInput();

                    }

                  }

                  if (isset($empresa->id)) {

                  }else{

                    $dominio=explode('@', $request->email);

                    if(isset($dominio[1])){

                    $empresa=AlpEmpresas::where('dominio',$dominio[1])->first();

                    }

                  }

                  $id_empresa=0;

                  if (isset($empresa->id)) {

                      $id_empresa=$empresa->id;

                  }

                    $data = array(

                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => $request->habeas_cliente,
                    'cod_oracle_cliente' =>$request->telefono_cliente,
                    'marketing_email'=> '1',
                    'marketing_sms'=> '1',
                    'estado_masterfile' =>'1',
                    'id_empresa' =>$id_empresa,               
                    'id_embajador' =>'0',               
                    'id_user' =>0,               

                    );

                  $cliente=AlpClientes::create($data);

                   if ($id_empresa==0) {

                    $role = Sentinel::findRoleById(9);

                        $user_history = array(
                        'id_cliente' => $user->id,
                        'estatus_cliente' => "Activado",
                        'notas' => "Ha sido registrado satisfactoriamente",
                        'id_user'=>$user->id
                         );


                        AlpClientesHistory::create($user_history);

                    }else{

                      $role = Sentinel::findRoleById(12);

                       $user_history = array(
                        'id_cliente' => $user->id,
                        'estatus_cliente' => "Activado",
                        'notas' => "Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa,
                        'id_user'=>$user->id
                         );

                        AlpClientesHistory::create($user_history);

                    }

                    $role->users()->attach($user);

                    $roleusuario=RoleUser::where('user_id', $user->id)->first();

                   

                       $data_c = array(

                            'cod_oracle_cliente' =>$request->telefono_cliente,

                            'estado_masterfile' =>'1'

                        );

                    $cliente->update($data_c);

                     $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';


                     if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {

                     #  Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida, $roleusuario));

                      #Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida, $roleusuario));

                     }

            }


            //if you set $activate=false above then user will receive an activation mail

            if (!$activate) {

                // Data to be used on the email view

                $data=[

                    'user_name' => $user->first_name .' '. $user->last_name,

                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),

                ];

                // Send the activation code through email



                if ($id_empresa==0) {

                    return secure_url('login');

                   # return redirect('login')->with('success', trans('auth/message.signup.success'));

                }else{

                    $user_history = array(

                        'id_client' => $user->id,

                        'estatus_cliente' => "Activado",

                        'notas' => "Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa,

                        'id_user'=>$user->id

                         );

                    AlpClientesHistory::create($user_history);

                     $configuracion->mensaje_bienvenida="Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa.", debe esperar que su Usuario sea activado en un proceso interno, te notificaremos vía email su activación.";

                     return secure_url('login');

                     #return redirect('login?registro='.$user->id)->with('success', trans($mensaje));

                }

            }


            try {

             // $datos360= $this->datos360($user->id); 

            } catch (Exception $e) {

              

            }


              try {

               // $this->addibm($user);

              } catch (Exception $e) {

              }

            // login user automatically

            Sentinel::login($user, false);

            //Activity log for new account

            activity($user->full_name)

                ->performedOn($user)

                ->causedBy($user)

                ->log('Nueva Cuenta Creada');

              Mail::to($user->email)->send(new \App\Mail\NotificacionCorreo($user));

           #   Mail::to('miguelmachadoaa@gmail.com')->send(new \App\Mail\NotificacionCorreo($user));

           // return Redirect::route("clientes")->with('success', trans('auth/message.signup.success'));

           if (\Session::has('cart')) {

            $cart= \Session::get('cart');

            if(count($cart)>0){

              return secure_url('/cart/show');

            }else{

              return secure_url('/registro/gracias');
            }
  
          }else{

            return secure_url('/registro/gracias');

          }

              return secure_url('/clientes');

              #return redirect("/?registro=".time())->with('success', trans('Bienvenido a Alpina GO!. Ya puedes comprar todos nuestro productos y promociones. Alpina Alimenta tu vida. '));


        } catch (UserExistsException $e) {

            #$this->messageBag->add('email', trans('auth/message.account_already_exists'));

            return 'falseEmail';

        }

        // Ooops.. something went wrong

        return 'false';

        #return Redirect::back()->withInput()->withErrors($this->messageBag);

    }


    public function gracias()
    {

      $cart= \Session::get('cart');

      $user = Sentinel::getuser();

      if(isset($user->id)){

        return view('gracias', compact('user', 'cart'));

      }else{

       # return redirect('/')->with('success', 'Cerró Sesión Exitosamente');
        return redirect('/');

      }


    }






    /**

     * Account sign up form processing.

     *

     * @return Redirect

     */

  




    /**

     * Forgot password page.

     *

     * @return View

     */

    public function getForgotPassword()

    {

        // Show the page

        return view('forgotpwd');



    }



    /**

     * Forgot password form processing page.

     * @param Request $request

     * @return Redirect

     */

    public function postForgotPassword(Request $request)
    {



       // dd($request);

    #  dd($request->all());

        try {

            // Get the user password recovery code

            $user = Sentinel::findByCredentials(['email' => $request->email]);

            if (!$user) {

                return Redirect::route('olvido-clave')->with('error', trans('auth/message.account_email_not_found'));

            }

            $activation = Activation::completed($user);

            if (!$activation) {


                return Redirect::route('olvido-clave')->with('error', trans('auth/message.account_not_activated'));

            }

            $reminder = Reminder::exists($user) ?: Reminder::create($user);

            $r=Reminder::where('user_id', '=', $user->id)->first();


            $data=[

                'user_name' => $user->first_name .' '. $user->last_name,

                'forgotPasswordUrl' => URL::route('olvido-clave-confirm', [$user->id, $r->code])

            ];

            // Send the activation code through email

            Mail::to($user->email)

                ->send(new \App\Mail\RecuperarClave($data));



            Mail::to('crearemosweb@gmail.com')

                ->send(new \App\Mail\RecuperarClave($data));







        } catch (UserNotFoundException $e) {

            // Even though the email was not found, we will pretend

            // we have sent the password reset code through email,

            // this is a security measure against hackers.

        }



        //  Redirect to the forgot password

        return back()->with('success', trans('auth/message.forgot-password.success'));

    }



    /**

     * Forgot Password Confirmation page.

     *

     * @param  string $passwordResetCode

     * @return View

     */

    public function getForgotPasswordConfirm(Request $request, $userId, $passwordResetCode = null)
    {

        if (!$user = Sentinel::findById($userId)) {

            // Redirect to the forgot password page
            return Redirect::route('forgot-password')->with('error', trans('auth/message.account_not_found'));

        }


        if($reminder = Reminder::exists($user))
        {

            $r=Reminder::where('user_id', '=', $user->id)->first();

            if($passwordResetCode == $r->code)
            {

                return view('forgotpwd-confirm', compact(['userId', 'passwordResetCode']));

            }else{

                return 'code does not match';

            }

        }else{

            return 'does not exists';

        }


    }




    /**

     * Forgot Password Confirmation form processing page.

     *

     * @param  string $passwordResetCode

     * @return Redirect

     */

    public function postForgotPasswordConfirm(PasswordResetRequest $request, $userId, $passwordResetCode = null)

    {



        $user = Sentinel::findById($userId);

     #   dd($request->all());

        $c=Reminder::where('code', $passwordResetCode)->first();

        if(isset($c->id)){

          $user->password = Hash::make($request->password);

          $user->save();

        return Redirect::route('login')->with('success', trans('auth/message.forgot-password-confirm.success'));



        }

        #dd($c);

        if (!$reminder = Reminder::complete($user, $passwordResetCode, $request->get('password'))) {

            // Ooops.. something went wrong

            return Redirect::route('login')->with('error', trans('auth/message.forgot-password-confirm.error'));

        }



        // Password successfully reseted

        return Redirect::route('login')->with('success', trans('auth/message.forgot-password-confirm.success'));

    }



    /**

     * Contact form processing.

     * @param Request $request

     * @return Redirect

     */

    public function postContact(Request $request)

    {

        $data = [

            'contact-name' => $request->get('contact-name'),

            'contact-email' => $request->get('contact-email'),

            'contact-msg' => $request->get('contact-msg'),

        ];





        // Send Email to admin

        Mail::to('email@domain.com')

            ->send(new Contact($data));



        // Send Email to user

        Mail::to($data['contact-email'])

            ->send(new ContactUser($data));



        //Redirect to contact page

        return redirect('contact')->with('success', trans('auth/message.contact.success'));

    }



    public function showFrontEndView($name=null)

    {

        if(View::exists($name))

        {

            return view($name);

        }

        else

        {

            abort('404');

        }

    }









    /**

     * Logout page.

     *

     * @return Redirect

     */

    public function getLogout()

    {

        if (Sentinel::check()) {



        $carrito= \Session::get('cr');



        $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();



        foreach ($cupones as $cupon) {

          

          $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();



          $c->delete();



        }



            \Session::forget('cart');

            \Session::forget('orden');

            \Session::forget('cr');



            //Activity log

            $user = Sentinel::getuser();

            activity($user->full_name)

                ->performedOn($user)

                ->causedBy($user)

                ->log('LoggedOut');

            // Log the user out

            Sentinel::logout();



            // Redirect to the users page

            return redirect('/')->with('success', 'Cerró Sesión Exitosamente');

        } else {



            // Redirect to the users page

            return redirect('/')->with('error', 'Debes Iniciar Sesión');

        }



    }



    public function postRegisterEmbajador(UserRequest $request)

    {



         $configuracion=AlpConfiguracion::where('id', '1')->first();



         if($configuracion->user_activation=0){



            $activate=true;



            $masterfi=1;



         }else{



            $activate=false;



            $masterfi=0;



         }







        try {

            // Register the user

            $user = Sentinel::register($request->only(['first_name', 'last_name', 'email', 'password', 'gender']), $activate);





           if ($request->gender=='male') {

               $genero=2;

           }else{

                $genero=1;

           }



             $data = array(

                'id_user_client' => $user->id, 

                'id_type_doc' => '1', 

                'doc_cliente' =>'', 

                'genero_cliente' =>$genero, 

                'habeas_cliente' => 0,

                'estado_masterfile' =>0,

                'id_empresa' =>'0',               

                'id_embajador' =>$request->referido,               

                'id_user' =>$user->id,               

            );



            AlpClientes::create($data);



            $user_embajador=User::where('id', $request->referido)->first();



             $data_embajador = array(

                'id_cliente' => $user->id, 

                'id_embajador' => $request->referido, 

                'notas'=>'Se ha registrado asignado el embajador id '.$user_embajador->first_name.' '.$user_embajador->last_name,

                'id_user' => $user->id

            );



            AlpClientesEmbajador::create($data_embajador);



            $user_history = array(

            'id_cliente' => $user->id,

            'estatus_cliente' => "Activado",

            'notas' => "Ha sido registrado satisfactoriamente",

            'id_user'=>$user->id

             );



            AlpClientesHistory::create($user_history);





            $referido=User::where('id',$request->referido )->firts();





            $user_history = array(

            'id_cliente' => $user->id,

            'estatus_cliente' => "Pendiente",

            'notas' => "Ha sido registrado satisfactoriamente como referido de ".$referido->first_name." ".$referido->last_name,

            'id_user'=>$user->id

             );



            AlpClientesHistory::create($user_history);





            //add user to 'User' group

            $role = Sentinel::findRoleById(11);

            $role->users()->attach($user);

            //if you set $activate=false above then user will receive an activation mail

            if (!$activate) {

                // Data to be used on the email view



                $data=[

                    'user_name' => $user->first_name .' '. $user->last_name,

                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),

                ];

                // Send the activation code through email



                

                Mail::to($user->email)

                    ->send(new Register($data));



                Mail::to('crearemosweb@gmail.com')

                    ->send(new Register($data));



                //Redirect to login page

                return redirect('login')->with('success', trans('auth/message.signup.success'));

            }

            // login user automatically

            Sentinel::login($user, false);

            //Activity log for new account

            activity($user->full_name)

                ->performedOn($user)

                ->causedBy($user)

                ->log('New Account created');





            // Redirect to the home page with success menu

            return Redirect::route("clientes")->with('success', trans('auth/message.signup.success'));



        } catch (UserExistsException $e) {

            $this->messageBag->add('email', trans('auth/message.account_already_exists'));

        }



        // Ooops.. something went wrong

        return Redirect::back()->withInput()->withErrors($this->messageBag);

    }



     /**

     * Get Ajax Request and restun Data

     *

     * @return \Illuminate\Http\Response

     */

    public function selectState($id)

    {

        $states = DB::table("config_states")

                    ->where("country_id",$id)

                    ->pluck("state_name","id")->all();

        $states['0'] = 'Seleccione Departamento';

        return json_encode($states);

    }



    /**

     * Get Ajax Request and restun Data

     *

     * @return \Illuminate\Http\Response

     */

    public function selectCity($id)

    {

        $ad=AlpAlmacenDespacho::where('id_state', 0)->where('id_city', 0)->first();





      if (isset($ad->id)) {



        $cities = DB::table("config_cities")

                    ->where("state_id",$id)

                    ->pluck("city_name","id")->all();



        

      }else{



        $ad=AlpAlmacenDespacho::where('id_state', $id)->where('id_city', 0)->first();



        if (isset($ad->id)) {



          $cities = DB::table("config_cities")

            ->where("state_id",$id)

            ->pluck("city_name","id")->all();



        }else{



          $cities = DB::table("config_cities")

            ->join('alp_almacen_despacho', 'config_cities.id', '=', 'alp_almacen_despacho.id_city')

            ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')

            ->where("config_cities.state_id",'=', $id)

            ->where("alp_almacenes.estado_registro",'=', '1')

            ->pluck("config_cities.city_name","config_cities.id")->all();





        }





      }

        

        $cities['0'] = 'Seleccione';

        return json_encode($cities);

    }





    private function inventario()
    {

       

       $id_almacen=$this->getAlmacen();

        $entradas = AlpInventario::groupBy('id_producto')
          ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
          ->join('alp_almacen_producto', 'alp_inventarios.id_producto', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_inventarios.operacion', '1')
          ->where('alp_inventarios.id_almacen', '=', $id_almacen)
          ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
          ->whereNull('alp_almacen_producto.deleted_at')
          ->groupBy('alp_inventarios.id_almacen')
              #->whereNull('alp_inventarios.deleted_at')
          ->get();



              $inv = array();

              foreach ($entradas as $row) {

                $inv[$row->id_producto]=$row->cantidad_total;

              }

            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->groupBy('alp_inventarios.id_almacen')
              #->whereNull('alp_inventarios.deleted_at')
              ->get();


              foreach ($salidas as $row) {



                if (isset($inv[$row->id_producto])) {

                  $inv[$row->id_producto]=$inv[$row->id_producto]-$row->cantidad_total;

                }else{

                  $inv[$row->id_producto]=0;

                }

                #$inv[$row->id_producto]=$inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;

    }




    private function inventarioAlmacen($id)
    {

       

       $id_almacen=$id;

        $entradas = AlpInventario::groupBy('id_producto')
          ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
          ->join('alp_almacen_producto', 'alp_inventarios.id_producto', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_inventarios.operacion', '1')
          ->where('alp_inventarios.id_almacen', '=', $id_almacen)
          ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
          ->whereNull('alp_almacen_producto.deleted_at')
          ->groupBy('alp_inventarios.id_almacen')
              #->whereNull('alp_inventarios.deleted_at')
          ->get();



              $inv = array();

              foreach ($entradas as $row) {

                $inv[$row->id_producto]=$row->cantidad_total;

              }

            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->groupBy('alp_inventarios.id_almacen')
              #->whereNull('alp_inventarios.deleted_at')
              ->get();


              foreach ($salidas as $row) {



                if (isset($inv[$row->id_producto])) {

                  $inv[$row->id_producto]=$inv[$row->id_producto]-$row->cantidad_total;

                }else{

                  $inv[$row->id_producto]=0;

                }

                #$inv[$row->id_producto]=$inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;

    }





 private function combos()

    {



      $c=AlpProductos::where('tipo_producto', '2')->get();

      

      $inventario=$this->inventario();



      $combos = array();



      foreach ($c as $co) {



        $ban=0;

        

        $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.slug as slug', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.imagen_producto as imagen_producto')

        ->join('alp_productos', 'alp_combos_productos.id_producto', '=', 'alp_productos.id')

        ->whereNull('alp_productos.deleted_at')

        ->where('id_combo', $co->id)

        ->get();



        foreach ($lista as $l) {



            if (isset($inventario[$l->id_producto])) {

                

                if($inventario[$l->id_producto]>$l->cantidad){



                }else{



                $ban=1;



                }



            }else{



                $ban=1;

            }

            

        }





        if ($ban==0) {



            $combos[$co->id]=$lista;

        }



      }



      return $combos;

    }





///////////////////////Funciones de IBM//////////////////////////////////////////











public function makeRequest($endpoint, $jsessionid, $xml, $ignoreResult = false)

{



    $url = $this->getApiUrl($endpoint, $jsessionid);







    echo  $url.'<br>';



    



    $xmlObj = new \SimpleXmlElement($xml);







    $request = $xmlObj->asXml();







    $curl = curl_init();



    curl_setopt($curl, CURLOPT_URL, $url);



    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



    curl_setopt($curl, CURLOPT_POST, true);



    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);



    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);



    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);



    curl_setopt($curl, CURLINFO_HEADER_OUT, true);







    $headers = array(



        'Content-Type: text/xml; charset=UTF-8',



        'Content-Length: ' . strlen($request),



    );







    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);



    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);



    curl_setopt($curl, CURLOPT_TIMEOUT, 60);







    $response = @curl_exec($curl);







    if (false === $response) {



        //throw new Exception('CURL error: ' . curl_error($curl));

        //

        return false;



    }







    curl_close($curl);







    if (true === $response || !trim($response)) {



       // throw new Exception('Empty response from WCA');

       // 

       $response=false;

       

       return false;



    }





    if ($response==false) {



      $xmlResponse = false;

     

    }else{



      $xmlResponse = simplexml_load_string($response);

    }

    







    if (false === $ignoreResult) {



        if (false === isset($xmlResponse->Body->RESULT)) {



           // var_dump($xmlResponse);



            //throw new Exception('Unexpected response from WCA');

            //

            return false;



        }else{



          return $xmlResponse->Body->RESULT;

        }







        



    }







    return $xmlResponse->Body;



}







public function getApiUrl($endpoint, $jsessionid)

{

    return $endpoint . ((null === $jsessionid)



        ? ''



        : ';jsessionid=' . urlencode($jsessionid));



}







   public function xmlToJson($xml)

  {



      return json_encode($xml);



  }







  public function xmlToArray($xml)



  {



      $json = $this->xmlToJson($xml);



      return json_decode($json, true);



  }























 private function getAlmacen(){

    $tipo=0;

      //  if (isset(Sentinel::getUser()->id)) {

        if (1==0) {

            # code...

            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

             $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();


            if (isset($d->id)) {

            }else{


                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();

            }

            if (isset($d->id)) {


               \Session::put('ciudad', $d->city_id);

              $tipo=0;

            if ($role->role_id=='14') {

              $tipo=1;

            }


               if ($d->id_barrio==0) {

                     $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')

                    ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')

                    ->where('alp_almacenes.tipo_almacen', '=', $tipo)

                    ->where('alp_almacen_despacho.id_city', $d->city_id)

                    ->where('alp_almacenes.estado_registro', '=', '1')

                    ->first();

                    

                }else{



                     $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')

                    ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')

                    ->where('alp_almacenes.tipo_almacen', '=', $tipo)

                    ->where('alp_almacen_despacho.id_barrio', $d->id_barrio)

                    ->where('alp_almacenes.estado_registro', '=', '1')

                    ->first();



                    if (isset($ad->id)) {

                        # code...

                    }else{





                        $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')

                        ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')

                        ->where('alp_almacenes.tipo_almacen', '=', $tipo)

                        ->where('alp_almacen_despacho.id_city', $d->city_id)

                        ->where('alp_almacenes.estado_registro', '=', '1')

                        ->first();

                    }





                }



                if (isset($ad->id)) {

                # code...

                }else{



                  $c=City::where('id', $d->city_id)->first();



                  if (isset($c->id)) {



                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')

                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')

                ->where('alp_almacenes.tipo_almacen', '=', $tipo)

                ->where('alp_almacen_despacho.id_city', '0')

                ->where('alp_almacen_despacho.id_state', $c->state_id)

                ->where('alp_almacenes.estado_registro', '=', '1')

                ->first();

                    # code...

                  }



                  



                  if (isset($ad->id)) {

                    

                  }else{



                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')

                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')

                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)

                  ->where('alp_almacen_despacho.id_city', '0')

                  ->where('alp_almacen_despacho.id_state', '0')->where('alp_almacenes.estado_registro', '=', '1')->first();



                  }



                }



                if (isset($ad->id)) {



                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->first();



                  $id_almacen=$almacen->id;

                  # code...

                }else{



                   $almacen=AlpAlmacenes::where('defecto', '1')->first();



                    if (isset($almacen->id)) {

                      $id_almacen=$almacen->id;

                    }else{

                      $id_almacen='1';

                    }



                }





































            }else{



              $almacen=AlpAlmacenes::where('defecto', '1')->first();



                if (isset($almacen->id)) {

                  $id_almacen=$almacen->id;

                }else{

                  $id_almacen='1';

                }

                   

            }



        }else{ //no esta logueado 





           # $ciudad= \Session::get('ciudad');
            $almacen= \Session::get('almacen');



            #dd($ciudad);



            if (isset($almacen)) {


              $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacenes.id', $almacen)
                ->where('alp_almacenes.estado_registro', '=', '1')
                ->first();


                if (isset($ad->id)) {

                # code...

                }else{


                #  $c=City::where('id', $ciudad)->first();


                  if (isset($c->id)) {

                      $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                      ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                      ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                      ->where('alp_almacen_despacho.id_city', '0')
                      ->where('alp_almacen_despacho.id_state', $c->state_id)
                      ->where('alp_almacenes.estado_registro', '=', '1')
                      ->first();

                  }

                  if (isset($ad->id)) {

                  }else{


                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacenes.estado_registro', '=', '1')
                  ->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->where('alp_almacenes.estado_registro', '=', '1')->first();

                  $id_almacen=$almacen->id;

                  # code...

                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                   if (isset($almacen->id)) {

                      $id_almacen=$almacen->id;

                    }else{

                      $id_almacen='1';

                    }

                }


            }else{

               $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

              if (isset($almacen->id)) {

                  $id_almacen=$almacen->id;

                }else{

                  $id_almacen='1';

                }

            }















           

        

        }



       # dd($id_almacen);



      return $id_almacen;



    }









    private function addibm($user)

    {





      $u=User::where('id', $user->id)->first();



      $c=AlpClientes::where('id_user_client', $u->id)->first();

        

        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();

        

        $pod = 0;

        $username = $configuracion->username_ibm;

        $password = $configuracion->password_ibm;

        $endpoint = $configuracion->endpoint_ibm;

        $jsessionid = null;



        $baseXml = '%s';

        $loginXml = '';

        $getListsXml = '%s%s';

        $logoutXml = '';



        try {



        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';



        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));



       // print_r($result);



        $jsessionid = $result['SESSIONID'];



            $xml='<Envelope><Body><AddRecipient><LIST_ID>8739683</LIST_ID><SYNC_FIELDS><SYNC_FIELD><NAME>EMAIL</NAME><VALUE>'.$user->email.'</VALUE></SYNC_FIELD></SYNC_FIELDS><UPDATE_IF_FOUND>true</UPDATE_IF_FOUND><COLUMN><NAME>Email</NAME><VALUE>'.$user->email.'</VALUE></COLUMN><COLUMN><NAME>Fuente_ecommerce</NAME><VALUE>Yes</VALUE></COLUMN><COLUMN><NAME>Nombres</NAME><VALUE>'.$user->first_name.'</VALUE></COLUMN><COLUMN><NAME>Apellidos</NAME><VALUE>'.$user->last_name.'</VALUE></COLUMN></AddRecipient></Body></Envelope>';





            activity()->withProperties($xml)->log('registro-xml_ibm_add_recipiente');



            $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));



            activity()->withProperties($result2)->log('registro-xml_ibm_add_result');





        $xml = '<Envelope>

          <Body>

          <Logout/>

          </Body>

          </Envelope>';



              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));



              activity()->withProperties($result)->log('registro-xml_ibm_add_result2');



            if (isset($result2['SUCCESS'])) {

              

              $data_u = array(

                'estatus_ibm' => 1, 

                'json_ibm' => json_encode($result2) 

              );



              $c->update($data_u);



            }else{



              $data_u = array(

                'estatus_ibm' => 0, 

                'json_ibm' => json_encode($result2) 

              );



              $c->update($data_u);



            }



              return $result2['SUCCESS'];



              $jsessionid = null;



          } catch (Exception $e) {



              die("\nException caught: {$e->getMessage()}\n\n");



              return 'FALSE';



          }

    }







     public function getmasvendidos($token)

    {



      $configuracion=AlpConfiguracion::first();



      if ($configuracion->token_api==$token) {





          $productos=AlpProductos::select(

          'alp_productos.*', 

          'alp_marcas.nombre_marca as nombre_marca', 

          'alp_categorias.nombre_categoria as nombre_categoria',

          DB::raw('sum(alp_ordenes_detalle.cantidad)  as cantidad_producto')



        )

        ->join('alp_marcas', 'alp_productos.id_marca', '=','alp_marcas.id')

        ->join('alp_categorias', 'alp_productos.id_categoria_default', '=','alp_categorias.id')

        ->join('alp_ordenes_detalle', 'alp_productos.id', '=','alp_ordenes_detalle.id_producto')

        ->where('alp_productos.estado_registro','=',1)

        ->groupBy('alp_productos.id')

        ->orderBy('cantidad_producto', 'desc')

        ->limit(20)

        ->get();



        $prods = array();



        foreach ($productos as $p) {





          if (!is_numeric($p->cantidad)) {

            $pum='';

          }else{

            $pum=number_format($p->precio_base/$p->cantidad, '2').' '.$p->unidad;

          }

         

          $ps = array(

            'nombre_producto' => $p->nombre_producto, 

            'presentacion_producto' => $p->presentacion_producto, 

            'referencia_producto' => $p->referencia_producto, 

            'referencia_producto_sap' => $p->referencia_producto_sap, 

            'descripcion_corta' => $p->descripcion_corta, 

            'descripcion_larga' => $p->descripcion_larga, 

            'enlace_youtube' => $p->enlace_youtube, 

            'imagen_producto' => secure_url('uploads/productos/'.$p->imagen_producto), 

            'slug' => $p->slug, 

            'enlace_producto' => secure_url('producto/'.$p->slug), 

            'precio_base' => $p->precio_base, 

            'pum' => $pum, 

            'medida' => $p->medida, 

            'nombre_marca' => $p->nombre_marca, 

            'nombre_categoria' => $p->nombre_categoria

          );



          $prods[]=$ps;



        }

        

      }else{





        $prods = array();





      }

     return response(json_encode($prods), 200) ->header('Content-Type', 'application/json');

    }




    private function datos360($id_user)

    {



      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();



      $user=User::where('id', $id_user)->first();



      $c=AlpClientes::where('id_user_client', $user->id)->first();



      $data = array(

        'first_name' =>$user->first_name,

        'last_name' =>$user->last_name,

        'dob' =>$user->dob,

        'genero_cliente' =>$c->genero_cliente,

        'doc_cliente' =>$c->doc_cliente,

        'telefono_cliente' =>$c->telefono_cliente,

        'marketing_email' =>$c->marketing_email,

        'marketing_sms' =>$c->marketing_sms,

        'eliminar_cliente' =>0,

        'email' =>$user->email,

      );



      $d = array();



      $d[]=$data;



      $dataraw=json_encode($d);



      $urls='https://alpinavista360webapp03.azurewebsites.net/api/UsuarioAlpinaGo/Add';



      activity()->withProperties($dataraw)->log('360 api add');



      $ch = curl_init();



      curl_setopt($ch, CURLOPT_URL, $urls);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt($ch, CURLOPT_POST, 1);

      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 

      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);



      $headers = array();

      $headers[] = 'Content-Type: application/json';

      $headers[] = 'Authorization: Basic zHnI1jLI3GH88tT0Pu6w7Q==';

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      try {

        $result = curl_exec($ch);

      } catch (Exception $e) {

      }



      

      if (curl_errno($ch)) {

          // Log::info('Error:' . curl_error($ch));

      }

      

      curl_close($ch);



      $res=json_decode($result);



      activity()->withProperties($result)->log('360 respuesta add');



      $notas='Registro de orden en api 360 res.';



      return 1;

      

    }









    private function datos360update($id_user)
    {


      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $user=User::where('id', $id_user)->first();

      $c=AlpClientes::where('id_user_client', $user->id)->first();


      $data = array(
        'first_name' =>$user->first_name,
        'last_name' =>$user->last_name,
        'dob' =>$user->dob,
        'genero_cliente' =>$c->genero_cliente,
        'doc_cliente' =>$c->doc_cliente,
        'telefono_cliente' =>$c->telefono_cliente,
        'marketing_email' =>$c->marketing_email,
        'marketing_sms' =>$c->marketing_sms,
        'eliminar_cliente' =>0,
        'email' =>$user->email,
      );

      $d = array();

      $d[]=$data;

      $dataraw=json_encode($d);

      $urls='https://alpinavista360webapp03.azurewebsites.net/api/UsuarioAlpinaGo/Update';

      activity()->withProperties($dataraw)->log('360 api update');

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $urls);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Authorization: Basic zHnI1jLI3GH88tT0Pu6w7Q==';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      try {

        $result = curl_exec($ch);

      } catch (Exception $e) {

      }

      if (curl_errno($ch)) {

           Log::info('Error:' . curl_error($ch));

      }

      curl_close($ch);

      $res=json_decode($result);

      activity()->withProperties($result)->log('360 respuesta update');

      $notas='Registro de orden en api 360 res.';

      return 1;

    }


  public function get360(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/get360 ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/get360');

        }

      $content = $request->getContent();

      $datos = json_decode($content, true);

       activity()->withProperties($datos)->log('FrontEndController/get360_datosrecibidos');

       $u=AlpConfiguracion::where('public_key_360', '=', $datos[0]['key'])->first();

       if(isset($u->id)){


        if($datos[0]['hash']==md5($datos[0]['fecha'].$u->private_key_360)){

        }else{
 
           $data = array(
               'estatus' =>false, 
               'mensaje'=>'Credenciales Invalidas', 
               'cod'=>'501'
           );
 
           return json_encode($data);
        }
 



       }else{

        $data = array(
            'estatus' =>false, 
            'mensaje'=>'Credenciales Invalidas', 
            'cod'=>'501'
        );

        return json_encode($data);

       }
       
       


          $r="false";

       if (count($datos)) {

            foreach ($datos as $dato ) {

          activity()
          ->withProperties($dato)->log('FrontEndController/get360_2.1');

          activity() ->withProperties($dato['email'])->log('FrontEndController/getCompramas2');

              $user=User::where('email', '=', $dato['email'])->first();

              if (isset($user->id)) {

                $r="true";

                 $c=AlpClientes::where('id_user_client', $user->id)->first();

                  $data_user = array(

                    'first_name' =>$dato['first_name'],

                    'last_name' =>$dato['last_name'],

                    'dob' =>$dato['dob'],

                  );


                   $data = array(

                //    'genero_cliente' =>$dato['genero_cliente'],

                //    'doc_cliente' =>$dato['doc_cliente'],

                //    'telefono_cliente' =>$dato['telefono_cliente'],

                    'marketing_email' =>$dato['marketing_email'],

                    'marketing_sms' =>$dato['marketing_sms'],

                    'eliminar_cliente' =>0,

                  );


                   $c->update($data);

                }

            } //end foreach datos

       } //(end if hay resspuessta)

    return response(json_encode($r), 200) ->header('Content-Type', 'application/json');

  }


  public function get360actualizar(Request $request)
  {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/get360actuaizar ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/get360actuaizar');

        }

      $content = $request->getContent();

      $datos = json_decode($content, true);

       activity()->withProperties($datos)->log('FrontEndController/get360actuaizar');

       $u=AlpConfiguracion::where('public_key_360', '=', $datos[0]['key'])->first();

       if(isset($u->id)){


        if($datos[0]['hash']==md5($datos[0]['fecha'].$u->private_key_360)){

        }else{
 
           $data = array(
               'estatus' =>false, 
               'mensaje'=>'Credenciales Invalidas', 
               'cod'=>'501'
           );
 
           return json_encode($data);
        }
 



       }else{

        $data = array(
            'estatus' =>false, 
            'mensaje'=>'Credenciales Invalidas', 
            'cod'=>'501'
        );

        return json_encode($data);

       }




    //  dd($datos);

          $r="false";

       if (count($datos)) {

            foreach ($datos as $dato ) {

              activity()->withProperties($dato)->log('FrontEndController/get 360 actualizar');

              $user=User::where('email', '=', $dato['email'])->first();


              if (isset($user->id)) {

                $r="true";

                 $c=AlpClientes::where('id_user_client', $user->id)->withTrashed()->first();

                 if (isset($c->id)) {

                   $data_user = array(
                    'first_name' =>$dato['first_name'],
                    'last_name' =>$dato['last_name'],
                    'gender' =>$dato['genero_cliente'],
                    'dob' =>$dato['dob'],
                  );

                   $data = array(
                    'marketing_email' =>$dato['marketing_email'],
                    'marketing_sms' =>$dato['marketing_sms'],
                    'genero_cliente' =>$dato['genero_cliente'],
                    'telefono_cliente' =>$dato['telefono_cliente'],
                    'doc_cliente' =>$dato['doc_cliente'],
                    'eliminar_cliente' =>0,
                  );

                   $c->update($data);
                   $user->update($data_user);


                   if ($dato['eliminar_cliente']=='1') {

                    //dd($dato);
                     $c->delete();
                   }

                 }

                }
                
            } //end foreach datos

       } //(end if hay resspuessta)

    return response(json_encode($r), 200) ->header('Content-Type', 'application/json');
   
  }


 public function get360consultar(Request $request)
  {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/get360actuaizar ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/get360actuaizar');

        }
        
        $content = $request->all();
        
        $datos1 = json_encode($content, true);

        $datos = json_decode($datos1, true);

        activity()->withProperties($datos)->log('FrontEndController/get360actuaizar');

        $u=AlpConfiguracion::where('public_key_360', '=', $datos['key'])->first();

        if(isset($u->id)){

          if($datos['hash']==md5($datos['fecha'].$u->private_key_360)){

          }else{
  
            $data = array(
              'estatus' =>false, 
              'mensaje'=>'Credenciales Invalidas', 
              'cod'=>'501'
            );
  
            return json_encode($data);
        }

      }else{

        $data = array(
          'estatus' =>false, 
          'mensaje'=>'Credenciales Invalidas', 
          'cod'=>'501'
        );

        return json_encode($data);

      }

      $modificados = array();

        if (is_array($datos)) {

          if (count($datos)) {

            if(isset($datos['fechaInicio']) && $datos['fechaFinal']){

              if(is_null($datos['fechaInicio']) || is_null($datos['fechaFinal'])){

                  $data = array(
                    'estatus' =>false, 
                    'mensaje'=>'Datos de fecha no deben ser nulos', 
                    'cod'=>'501'
                  );
          
                  return json_encode($data);

              }else{




                activity()->withProperties($datos)->log('FrontEndController/get 360 actualizar');

                $users=User::where('updated_at', '>=', $datos['fechaInicio'])->where('updated_at', '<=', $datos['fechaFinal'])->get();
    
                foreach ($users as $u) {
    
                  $c=AlpClientes::where('id_user_client', $u->id)->first();
    
                  if (isset($c->id)) {
    
                    $dir = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                        ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                        ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                        ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                        ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                        ->where('alp_direcciones.id_client', $u->id)
                        ->where('alp_direcciones.default_address', '=', '1')
                        ->first();
    
    
                        if (isset($dir->id)) {
                          # code...
                        }else{
    
                          $dir = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                          ->where('alp_direcciones.id_client', $u->id)
                          ->first();
    
    
                        }
    
    
    
    
                        if (isset($dir->id)) {
                          
                           
    
                           $direccion=$dir->state_name.' , '.$dir->city_name.' '.$dir->nombre_estructura.' '.$dir->principal_address .' #'. $dir->secundaria_address .'-'.$dir->edificio_address.', '.$dir->detalle_address.', '.$dir->barrio_address.' Nota:'.$dir->notas;
    
    
                        }else{
    
                          $direccion='';
                        }
    
                        if (is_null($c->deleted_at)) {
                          $eliminar=0;
                        }else{
                          $eliminar=1;
                        }
    
    
    
                        $data = array(
                          'first_name' =>$u->first_name,
                          'last_name' =>$u->last_name,
                          'dob' =>$u->dob,
                          'genero_cliente' =>$u->gender,
                          'doc_cliente' =>$c->doc_cliente,
                          'telefono_cliente' =>$c->telefono_cliente,
                          'direccion_cliente' =>$direccion,
                          'marketing_email' =>$c->marketing_email,
                          'marketing_sms' =>$c->marketing_sms,
                          'eliminar_cliente' =>$eliminar,
                          'email' =>$u->email,
                          'fecha_creacion' =>$u->created_at->format('d-m-Y H:i:s'),
                          'fecha_actualizacion' =>$u->updated_at->format('d-m-Y H:i:s')
                        );
    
    
                        $modificados[] = $data;
                  }//end if es cliente
                
                }//End foreach 





              }


            }else{

              $data = array(
                'estatus' =>false, 
                'mensaje'=>'Datos Incompletos', 
                'cod'=>'501'
              );
      
              return json_encode($data);

            }

              

           


          } //(end if hay resspuessta)

      } //(end if hay resspuessta)

      return response(json_encode($modificados), 200)->header('Content-Type', 'application/json');
   
  }




}

    