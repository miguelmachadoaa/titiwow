<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\CuponesRequest;
use App\Models\AlpCupones;
use App\Models\AlpCuponesCategorias;
use App\Models\AlpCuponesEmpresa;
use App\Models\AlpCuponesProducto;
use App\Models\AlpCuponesRol;
use App\Models\AlpCuponesUser;
use App\Models\AlpCategorias;
use App\Models\AlpProductos;
use App\Models\AlpEmpresas;
use App\Models\AlpClientes;
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
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $cupones = AlpCupones::all();
       


        // Show the page
        return view('admin.cupones.index', compact('cupones'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
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
        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();


        //var_dump($input);

        $data = array(
            'codigo_cupon' => $request->codigo_cupon, 
            'valor_cupon' => $request->valor_cupon, 
            'tipo_reduccion' => $request->tipo_reduccion, 
            'limite_uso' => $request->limite_uso, 
            'fecha_inicio' => $request->fecha_inicio, 
            'fecha_final' => $request->fecha_final, 
            'monto_minimo' => $request->monto_minimo, 
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
       
       $cupon = AlpCupones::find($id);

        return view('admin.cupones.edit', compact('cupon'));
    }


     public function addcategoria(Request $request)
    {

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_categoria' => $request->id_categoria, 
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

    public function addempresa(Request $request)
    {

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_empresa' => $request->id_empresa, 
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

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_producto' => $request->id_producto, 
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

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_cliente' => $request->id_cliente, 
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

         $user_id = Sentinel::getUser()->id;

       
        $data = array(
            'id_cupon' => $request->id_cupon, 
            'id_rol' => $request->id_rol, 
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




    public function configurar($id)
    {
       
       $cupon = AlpCupones::find($id);

       
       $empresas=AlpEmpresas::get();

       $roles=Roles::select('roles.id', 'roles.name')->get();

       $productos=AlpProductos::get();

       $categorias=AlpCategorias::get();

       $clientes = AlpClientes::select('alp_clientes.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
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


           $clientes_list = AlpCuponesUser::select('alp_cupones_user.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('alp_clientes', 'alp_cupones_user.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_cupones_user.id_cupon', $id)
          ->get();


          $roles_list = AlpCuponesRol::select('alp_cupones_rol.*', 'roles.name as name')
          ->join('roles', 'alp_cupones_rol.id_rol', '=', 'roles.id')
          ->where('alp_cupones_rol.id_cupon', $id)
          ->get();



        return view('admin.cupones.configurar', compact('cupon', 'categorias', 'clientes', 'empresas', 'productos', 'categorias_list', 'empresas_list', 'clientes_list', 'productos_list', 'roles', 'roles_list'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(CuponesRequest $request, $id)
    {
       $data = array(
            'codigo_cupon' => $request->codigo_cupon, 
            'valor_cupon' => $request->valor_cupon, 
            'tipo_reduccion' => $request->tipo_reduccion, 
            'limite_uso' => $request->limite_uso, 
            'fecha_inicio' => $request->fecha_inicio, 
            'fecha_final' => $request->fecha_final, 
            'monto_minimo' => $request->monto_minimo, 
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

    

}
