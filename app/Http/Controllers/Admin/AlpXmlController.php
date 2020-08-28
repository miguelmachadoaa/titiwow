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
        
        if (!Sentinel::getUser()->hasAnyAccess(['xml.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
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




        $productos = AlpProductos::select('alp_productos.*', 'alp_xml.id as xml_id', 'roles.name as name')
        ->join('alp_xml', 'alp_productos.id', '=', 'alp_xml.id_producto')
         ->join('roles', 'alp_xml.id_rol', '=', 'roles.id')
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_xml.deleted_at')
        ->get();


        $listaproductos=AlpProductos::where('estado_registro', '=', '1')->get();

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


        return view('admin.xml.index', compact('roles', 'prods', 'almacen', 'inventario', 'check', 'rolxml', 'listaproductos'));
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
        
        if (!Sentinel::getUser()->hasAnyAccess(['xml.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
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















public function addproducto(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/adddestacado ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/adddestacado');


        }

        $user_id = Sentinel::getUser()->id;


        if ($request->id_producto=='0') {


          AlpXml::where('id', '>', '0')->delete();

          $listaproductos=AlpProductos::where('estado_registro', '=', '1')->get();



          foreach ($listaproductos as $lp) {

            $data = array(
              'id_producto' => $lp->id, 
              'id_rol' => $request->id_rol, 
              'id_user' => $user->id
            );

           // dd($data);

            AlpXml::create($data);

          }
          


        }else{



          $p=AlpXml::where('id_producto', $request->id_producto)->where('id_rol', '=', $request->id_rol)->first();


          if (isset($p->id)) {
            # code...
          }else{


           $data = array(
              'id_producto' => $request->id_producto, 
              'id_rol' => $request->id_rol, 
              'id_user' => $user->id, 
            );

            AlpXml::create($data);

          }



        }

        




        $productos = AlpProductos::select('alp_productos.*', 'alp_xml.id as xml_id', 'roles.name as name')
        ->join('alp_xml', 'alp_productos.id', '=', 'alp_xml.id_producto')
         ->join('roles', 'alp_xml.id_rol', '=', 'roles.id')
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_xml.deleted_at')
        ->get();


        $listaproductos=AlpProductos::where('estado_registro', '=', '1')->get();

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




      $view= View::make('admin.xml.listaproductos', compact('prods', 'check','inventario','almacen'));

      $data=$view->render();

      return $data;


    }

 public function delproducto(Request $request){

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/deldestacado ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/deldestacado');


        }

         $user_id = Sentinel::getUser()->id;

         $xml=AlpXml::where('id', $request->id)->first();

         $xml->delete();

        $productos = AlpProductos::select('alp_productos.*', 'alp_xml.id as xml_id', 'roles.name as name')
        ->join('alp_xml', 'alp_productos.id', '=', 'alp_xml.id_producto')
        ->join('roles', 'alp_xml.id_rol', '=', 'roles.id')
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_xml.deleted_at')
        ->get();

        $listaproductos=AlpProductos::where('estado_registro', '=', '1')->get();

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


       $view= View::make('admin.xml.listaproductos', compact('prods', 'check','inventario','almacen'));

      $data=$view->render();

      return $data;


    }


    public function deltodos(Request $request){

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/deldestacado ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/deldestacado');


        }

         $user_id = Sentinel::getUser()->id;

         $xml=AlpXml::where('id', '>', 0)->delete();

         
       return redirect('admin/xml')->withInput()->with('success', trans('Se ha actualizado satisfactoriamente'));


    }



    public function data()
    {

        $productos = AlpProductos::select('alp_productos.*', 'alp_xml.id as xml_id', 'roles.name as name')
        ->join('alp_xml', 'alp_productos.id', '=', 'alp_xml.id_producto')
        ->join('roles', 'alp_xml.id_rol', '=', 'roles.id')
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_xml.deleted_at')
        ->get();






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





            $data = array();

          foreach($prods as $row){

              $imagen='<figure>
                        <img style="width: 60px;" src="'.secure_url('uploads/productos/'.$row->imagen_producto).'" data-src="'.secure_url('uploads/productos/60/'.$row->imagen_producto).'" alt="img">
                    </figure>';


                    $inv=0;



                 if(isset($inventario[$row->id][$almacen->id])){

                        $inv=$inventario[$row->id][$almacen->id];

                    }else{

                        $inv=0;

                    }


                    if($row->estado_registro==1){

                        $estado='<a href="#" class="label label-success">Si</a>';

                    }else{

                        $estado=' <a href="#" class="label label-danger">No</a>';

                    }

                    if(isset($check[$row->id])){

                        $c='<a href="#" class="label label-success">Activo</a>';

                    }else{

                        $c='<a href="#" class="label label-danger">Inactivo</a>';

                    }

                    $eliminar=' <button data-id="'.$row->xml_id.'" type="button" class="btn btn-danger delproducto">
                        Eliminar
                    </button>';




                 


               $data[]= array(
                 $imagen, 
                 $row->name, 
                 $row->nombre_producto, 
                 $row->referencia_producto,
                 $row->precio_base,
                 $row->precio_oferta,
                 $inv,
                 $estado,
                 $c,
                 $eliminar
              );

          }


          return json_encode( array('data' => $data ));

    }













}