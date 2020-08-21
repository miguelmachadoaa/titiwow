<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\CuponesRequest;
use App\Models\AlpCupones;
use App\Models\AlpCuponesCategorias;
use App\Models\AlpCuponesEmpresa;
use App\Models\AlpCuponesProducto;
use App\Models\AlpCuponesRol;
use App\Models\AlpCuponesMarcas;
use App\Models\AlpCuponesUser;
use App\Models\AlpCategorias;
use App\Models\AlpProductos;
use App\Models\AlpEmpresas;
use App\Models\AlpMarcas;
use App\Models\AlpClientes;
use App\Models\AlpAlmacenes;
use App\Models\AlpCuponesAlmacen;

use App\Imports\CuponesImport;
use App\Imports\CuponesGestionImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Roles;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpCuponesController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return 
     */
    public function index()
    {
        // Grab all the groups

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties($request->all())
                        ->log('cupones/index ');

        }else{

          activity()
          #->withProperties($request->all())
          ->log('cupones/index');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['cupones.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $cupones = AlpCupones::all();

        // Show the page
        return view('admin.cupones.index', compact('cupones'));
    }

     public function data()
    {
       
          $cupones = AlpCupones::all();

        $data = array();

        foreach($cupones as $row){

            $actions = "   <a href='".secure_url('/admin/cupones/'.$row->id.'/edit')."'>
            <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Cupon'></i>
        </a>

        <a href='".secure_url('admin/cupones/'.$row->id.'/configurar')."'>
            <i class='livicon' data-name='gear' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Configurar Cupon'></i>
        </a>


        <!-- let's not delete 'Admin' group by accident -->
        
        <a href='".secure_url('/admin/cupones/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
        <i class='livicon' data-name='remove-alt' data-size='18'
            data-loop='true' data-c='#f56954' data-hc='#f56954'
            title='Eliminar'></i>
         </a>";

               $data[]= array(
                 $row->id, 
                 $row->codigo_cupon, 
                 $row->valor_cupon, 
                 $row->tipo_reduccion, 
                 $row->limite_uso, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $actions
              );

          }

          return json_encode( array('data' => $data ));

          
      }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties($request->all())
                        ->log('cupones/create ');

        }else{

          activity()
          #->withProperties($request->all())
          ->log('cupones/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['cupones.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      


        // Show the page
        return view ('admin.cupones.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(CuponesRequest $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/store ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/store');


        }
        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();


        //var_dump($input);

        if($request->primeracompra==NULL){$request->primeracompra==0;}

        $data = array(
            'codigo_cupon' => $request->codigo_cupon, 
            'valor_cupon' => $request->valor_cupon, 
            'tipo_reduccion' => $request->tipo_reduccion, 
            'limite_uso' => $request->limite_uso, 
            'limite_uso_persona' => $request->limite_uso_persona, 
            'fecha_inicio' => $request->fecha_inicio, 
            'fecha_final' => $request->fecha_final, 
            'monto_minimo' => $request->monto_minimo, 
            'maximo_productos' => $request->maximo_productos, 
            'primeracompra' => $request->primeracompra, 
            'registrado' => $request->registrado, 
            'id_user' =>$user_id
        );
         
        $cupon=AlpCupones::create($data);

        if ($cupon->id) {

            return redirect('admin/cupones')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/cupones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        #->withProperties($request->all())
                        ->log('cupones/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])
          #->withProperties($request->all())
          ->log('cupones/edit');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['cupones.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      
       
       $cupon = AlpCupones::find($id);

        return view('admin.cupones.edit', compact('cupon'));
    }


     public function addcategoria(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties(['id'=>$id]))
                        ->withProperties($request->all())
                        ->log('cupones/addcategoria ');

        }else{

          activity()
          #->withProperties(['id'=>$id])
          ->withProperties($request->all())
          ->log('cupones/addcategoria');


        }

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_categoria' => $request->id_categoria, 
            'condicion' => $request->condicion,
            'user_id' => $request->user_id
        );



        AlpCuponesCategorias::create($data);

        $categorias_list=AlpCuponesCategorias::where('id_cupon', $request->id_cupon)
        ->get();

        $categorias_list = AlpCuponesCategorias::select('alp_cupones_categoria.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_cupones_categoria.id_categoria', '=', 'alp_categorias.id')
          ->where('alp_cupones_categoria.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listcategorias', compact('categorias_list'));

      $data=$view->render();

      return $data;


        return view('admin.cupones.edit', compact('cupon'));
    }

 public function delcategoria(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delcategoria ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delcategoria');


        }

         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesCategorias::where('id', $request->id)->first();

         $cat->delete();
       

        $categorias_list = AlpCuponesCategorias::select('alp_cupones_categoria.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_cupones_categoria.id_categoria', '=', 'alp_categorias.id')
          ->where('alp_cupones_categoria.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listcategorias', compact('categorias_list'));

      $data=$view->render();

      return $data;



    }




       public function addalmacen(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties(['id'=>$id]))
                        ->withProperties($request->all())
                        ->log('cupones/addalmacen ');

        }else{

          activity()
          #->withProperties(['id'=>$id])
          ->withProperties($request->all())
          ->log('cupones/addalmacen');


        }

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_almacen' => $request->id_almacen, 
            'condicion' => $request->condicion,
            'user_id' => $request->user_id
        );



        AlpCuponesAlmacen::create($data);

        $almacenes_list=AlpCuponesAlmacen::where('id_cupon', $request->id_cupon)
        ->get();

        $almacenes_list = AlpCuponesAlmacen::select('alp_cupones_almacen.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
          ->join('alp_almacenes', 'alp_cupones_almacen.id_almacen', '=', 'alp_almacenes.id')
          ->where('alp_cupones_almacen.id_cupon', $request->id_cupon)
          ->get();


          $view= View::make('frontend.cupones.listalmacen', compact('almacenes_list'));

      $data=$view->render();

      return $data;


        
    }

 public function delalmacen(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delalmacen ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delalmacen');


        }

         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesAlmacen::where('id', $request->id)->first();

         if (isset($cat->id)) {
           $cat->delete();
         }

         
       

         $almacenes_list = AlpCuponesAlmacen::select('alp_cupones_almacen.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
          ->join('alp_almacenes', 'alp_cupones_almacen.id_almacen', '=', 'alp_almacenes.id')
          ->where('alp_cupones_almacen.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listalmacen', compact('almacenes_list'));

      $data=$view->render();

      return $data;



    }





    public function addempresa(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/addempresa ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/addempresa');


        }



         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_empresa' => $request->id_empresa, 
            'condicion' => $request->condicion,
            'user_id' => $request->user_id
        );



        AlpCuponesEmpresa::create($data);

        

       $empresas_list = AlpCuponesEmpresa::select('alp_cupones_empresa.*', 'alp_empresas.nombre_empresa as nombre_empresa')
          ->join('alp_empresas', 'alp_cupones_empresa.id_empresa', '=', 'alp_empresas.id')
          ->where('alp_cupones_empresa.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listempresas', compact('empresas_list'));

      $data=$view->render();

      return $data;


    }

 public function delempresa(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delempresa ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delempresa');


        }

         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesEmpresa::where('id', $request->id)->first();

         $cat->delete();
       

        $empresas_list = AlpCuponesEmpresa::select('alp_cupones_empresa.*', 'alp_empresas.nombre_empresa as nombre_empresa')
          ->join('alp_empresas', 'alp_cupones_empresa.id_empresa', '=', 'alp_empresas.id')
          ->where('alp_cupones_empresa.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listempresas', compact('empresas_list'));

      $data=$view->render();

      return $data;


    }


    public function addproducto(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/addproducto ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/addproducto');


        }

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_producto' => $request->id_producto, 
            'condicion' => $request->condicion,
            'user_id' => $request->user_id
        );



        AlpCuponesProducto::create($data);

      
          $productos_list = AlpCuponesProducto::select('alp_cupones_producto.*', 'alp_productos.nombre_producto as nombre_producto')
          ->join('alp_productos', 'alp_cupones_producto.id_producto', '=', 'alp_productos.id')
          ->where('alp_cupones_producto.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listproductoss', compact('productos_list'));

      $data=$view->render();

      return $data;


    }

 public function delproducto(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delproducto ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delproducto');


        }

         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesProducto::where('id', $request->id)->first();

         $cat->delete();
       

          $productos_list = AlpCuponesProducto::select('alp_cupones_producto.*', 'alp_productos.nombre_producto as nombre_producto')
          ->join('alp_productos', 'alp_cupones_producto.id_producto', '=', 'alp_productos.id')
          ->where('alp_cupones_producto.id_cupon', $request->id_cupon)
          ->get();

          $view= View::make('frontend.cupones.listproductoss', compact('productos_list'));

      $data=$view->render();

      return $data;


    }



    public function addcliente(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/addcliente ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/addcliente');


        }



         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_cliente' => $request->id_cliente, 
            'condicion' => $request->condicion,
            'user_id' => $request->user_id
        );



        AlpCuponesUser::create($data);

      
          
           $clientes_list = AlpCuponesUser::select('alp_cupones_user.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('alp_clientes', 'alp_cupones_user.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_cupones_user.id_cupon', $request->id_cupon)
          ->get();


          $view= View::make('frontend.cupones.listclientes', compact('clientes_list'));

      $data=$view->render();

      return $data;


    }

 public function delcliente(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delcliente ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delcliente');


        }

         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesUser::where('id', $request->id)->first();

         $cat->delete();
       

            $clientes_list = AlpCuponesUser::select('alp_cupones_user.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('alp_clientes', 'alp_cupones_user.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_cupones_user.id_cupon', $request->id_cupon)
          ->get();


          $view= View::make('frontend.cupones.listclientes', compact('clientes_list'));

      $data=$view->render();

      return $data;


    }



      public function addrol(Request $request)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/addrol ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/addrol');


        }



         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_rol' => $request->id_rol, 
            'condicion' => $request->condicion,
            'user_id' => $request->user_id
        );



        AlpCuponesRol::create($data);

      
          
            $roles_list = AlpCuponesRol::select('alp_cupones_rol.*', 'roles.name as name')
          ->join('roles', 'alp_cupones_rol.id_rol', '=', 'roles.id')
          ->where('alp_cupones_rol.id_cupon', $request->id_cupon)
          ->get();


          $view= View::make('frontend.cupones.listroles', compact('roles_list'));

      $data=$view->render();

      return $data;


    }

 public function delrol(Request $request)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delrol ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delrol');


        }



         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesRol::where('id', $request->id)->first();

         $cat->delete();
       

           
            $roles_list = AlpCuponesRol::select('alp_cupones_rol.*', 'roles.name as name')
          ->join('roles', 'alp_cupones_rol.id_rol', '=', 'roles.id')
          ->where('alp_cupones_rol.id_cupon', $request->id_cupon)
          ->get();


          $view= View::make('frontend.cupones.listroles', compact('roles_list'));

      $data=$view->render();

      return $data;


    }


      public function addmarca(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/addmarca ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/addmarca');


        }

         $user_id = Sentinel::getUser()->id;
       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_marca' => $request->id_marca, 
            'condicion' => $request->condicion, 
            'user_id' => $request->user_id
        );

        AlpCuponesMarcas::create($data);

       $marcas_list = AlpCuponesMarcas::select('alp_cupones_marca.*', 'alp_marcas.nombre_marca as nombre_marca')
          ->join('alp_marcas', 'alp_cupones_marca.id_marca', '=', 'alp_marcas.id')
          ->where('alp_cupones_marca.id_cupon', $request->id_cupon)
          ->get();

          dd($marcas_list);

          $view= View::make('frontend.cupones.listmarcas', compact('marcas_list'));

      $data=$view->render();

      return $data;


    }

 public function delmarca(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/delmarca ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/delmarca');


        }



         $user_id = Sentinel::getUser()->id;


         $cat=AlpCuponesMarcas::where('id', $request->id)->first();

         $cat->delete();
       

       $marcas_list = AlpCuponesMarcas::select('alp_cupones_marca.*', 'alp_marcas.nombre_marca as nombre_marca')
          ->join('alp_marcas', 'alp_cupones_marca.id_marca', '=', 'alp_marcas.id')
          ->where('alp_cupones_marca.id_cupon', $request->id_cupon)
          ->get();

         $view= View::make('frontend.cupones.listmarcas', compact('marcas_list'));

      $data=$view->render();

      return $data;


    }




    public function configurar($id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('cupones/configurar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])
          ->log('cupones/configurar');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['cupones.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      


       
       $cupon = AlpCupones::find($id);

       
       $empresas=AlpEmpresas::where('estado_registro', '1')->get();

       $marcas=AlpMarcas::where('estado_registro', '1')->get();

       $almacenes=AlpAlmacenes::where('estado_registro', '1')->get();

       $roles=Roles::select('roles.id', 'roles.name')->get();

       $productos=AlpProductos::where('estado_registro', '1')->get();

       $categorias=AlpCategorias::where('estado_registro', '1')->get();

       $clientes = AlpClientes::select('alp_clientes.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_clientes.estado_registro', '1')
          ->get();

        //$roles = Roles::all();
        $roles = DB::table('roles')->whereIn('roles.id', [9, 10, 11, 12])->get();




       
        $categorias_list = AlpCuponesCategorias::select('alp_cupones_categoria.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_cupones_categoria.id_categoria', '=', 'alp_categorias.id')
          ->where('alp_cupones_categoria.id_cupon', $id)
          ->get();


        $empresas_list = AlpCuponesEmpresa::select('alp_cupones_empresa.*', 'alp_empresas.nombre_empresa as nombre_empresa')
          ->join('alp_empresas', 'alp_cupones_empresa.id_empresa', '=', 'alp_empresas.id')
          ->where('alp_cupones_empresa.id_cupon', $id)
          ->get();

          $productos_list = AlpCuponesProducto::select('alp_cupones_producto.*', 'alp_productos.nombre_producto as nombre_producto')
          ->join('alp_productos', 'alp_cupones_producto.id_producto', '=', 'alp_productos.id')
          ->where('alp_cupones_producto.id_cupon', $id)
          ->get();

          $marcas_list = AlpCuponesMarcas::select('alp_cupones_marca.*', 'alp_marcas.nombre_marca as nombre_marca')
          ->join('alp_marcas', 'alp_cupones_marca.id_marca', '=', 'alp_marcas.id')
          ->where('alp_cupones_marca.id_cupon', $id)
          ->get();


           $clientes_list = AlpCuponesUser::select('alp_cupones_user.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('alp_clientes', 'alp_cupones_user.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_cupones_user.id_cupon', $id)
          ->get();


          $roles_list = AlpCuponesRol::select('alp_cupones_rol.*', 'roles.name as name')
          ->join('roles', 'alp_cupones_rol.id_rol', '=', 'roles.id')
          ->where('alp_cupones_rol.id_cupon', $id)
          ->get();


          $almacenes_list = AlpCuponesAlmacen::select('alp_cupones_almacen.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
          ->join('alp_almacenes', 'alp_cupones_almacen.id_almacen', '=', 'alp_almacenes.id')
          ->where('alp_cupones_almacen.id_cupon', $id)
          ->get();



        return view('admin.cupones.configurar', compact('cupon', 'categorias', 'clientes', 'empresas', 'productos', 'categorias_list', 'empresas_list', 'clientes_list', 'productos_list', 'roles', 'roles_list', 'marcas', 'marcas_list', 'almacenes', 'almacenes_list'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/update ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/update');


        }

        if($request->primeracompra==NULL){$request->primeracompra==0;}

       $data = array(
            'codigo_cupon' => $request->codigo_cupon, 
            'valor_cupon' => $request->valor_cupon, 
            'tipo_reduccion' => $request->tipo_reduccion, 
            'limite_uso' => $request->limite_uso, 
            'fecha_inicio' => $request->fecha_inicio, 
            'fecha_final' => $request->fecha_final, 
            'monto_minimo' => $request->monto_minimo, 
            'maximo_productos' => $request->maximo_productos, 
            'primeracompra' => $request->primeracompra, 
            'registrado' => $request->registrado, 
            'limite_uso_persona' => $request->limite_uso_persona, 

        );
         
       $cupon = AlpCupones::find($id);
    
        $cupon->update($data);

        if ($cupon->id) {

            return redirect('admin/cupones')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/cupones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'cupones';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpCupones::find($id);

            $confirm_route = route('admin.cupones.delete', ['id' => $forma->id]);

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
                        ->withProperties(['id'=>$id])
                        ->log('cupones/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])
          ->log('cupones/destroy');


        }


        try {
            // Get group information
           
            $forma = AlpCupones::find($id);


            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.cupones.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.cupones.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cargarcupones()
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('cupones/cargarcupones ');

        }else{

          activity()
          ->log('cupones/cargarcupones');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['cupones.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      

      

        return view('admin.cupones.cargar');
    }
    


    public function import(Request $request) 
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('cupones/cargarcupones ');

        }else{

          activity()
          ->withProperties($request->all())->log('cupones/cargarcupones');


        }


        $archivo = $request->file('file_cupones');

        Excel::import(new CuponesImport, $archivo);
        
        return redirect('admin/cupones')->with('success', 'Cupones Cargados Exitosamente');
    }


      public function cargargestion()
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('cupones/cargarcupones ');

        }else{

          activity()
          ->log('cupones/cargarcupones');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['cupones.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      

      

        return view('admin.cupones.cargargestion');
    }
    


    public function postcargargestion(Request $request) 
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('cupones/postcargargestion ');

        }else{

          activity()
          ->withProperties($request->all())->log('cupones/postcargargestion');


        }


        $archivo = $request->file('file_cupones');

        Excel::import(new CuponesGestionImport, $archivo);
        
        return redirect('admin/cupones')->with('success', 'Cupones Cargados Exitosamente');
    }



}
