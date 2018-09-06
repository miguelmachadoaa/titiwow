<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use Intervention\Image\Facades\Image;
use DOMDocument;
use App\Http\Requests\ClientesRequest;
use App\Mail\Register;
use App\Mail\Restore;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Models\AlpClientes;
use App\Models\AlpTDocumento;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use DB;
use File;
use Hash;
use Illuminate\Support\Facades\Mail;
use URL;
use Validator;
use Yajra\DataTables\DataTables;


class AlpClientesController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)->get();


        // Show the page
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Get all the available groups
        $groups = DB::table('roles')->where('roles.id', '<>', 1)->get();

        $tdocumento = AlpTDocumento::all();

        // Show the page
        return view ('admin.clientes.create', compact('groups','tdocumento'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(ClientesRequest $request)
    {
        $user_id = Sentinel::getUser()->id;

         //upload image
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/perfiles/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }
        //check whether use should be activated by default or not
        $activate = $request->get('activate') ? true : false;

        try {
            // Register the user

            $cliente = Sentinel::register(
            ['email' => $request->email, 
            'first_name' => $request->first_name, 
            'last_name' =>$request->last_name, 
            'dob' =>$request->dob, 
            'pic' =>$request->pic, 
            'password' =>$request->password], $activate)->id;

            $data = array(
                'id_user_client' => $cliente, 
                'id_type_doc' => $request->id_type_doc, 
                'doc_cliente' =>$request->doc_cliente, 
                'genero_cliente' =>$request->genero_cliente, 
                'telefono_cliente' =>$request->telefono_cliente, 
                'marketing_cliente' =>$request->marketing_cliente,
                'habeas_cliente' =>$request->habeas_cliente[0],
                'estado_masterfile' =>$request->activate,
                'id_user' =>$user_id,               
            );

            AlpClientes::create($data);

            $user = Sentinel::findUserById($cliente);

            //add user to 'User' group
            $role = Sentinel::findRoleById($request->get('group'));
            if ($role) {
                $role->users()->attach($user);
            }
            //check for activation and send activation mail if not activated by default
            if (!$request->get('activate')) {
                // Data to be used on the email view
                $data =[
                    'user_name' => $user->first_name .' '. $user->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code])
                ];
                // Send the activation code through email
                /*Mail::to($user->email)
                    ->send(new Register($data));*/
            }
            // Activity log for New user create
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Nuevo usuario, creado por '.Sentinel::getUser()->full_name);
            // Redirect to the home page with success menu
            return Redirect::route('admin.clientes.index')->with('exito', trans('users/message.success.create'));

        } catch (LoginRequiredException $e) {
            $error = trans('admin/users/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = trans('admin/users/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = trans('admin/users/message.user_exists');
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
       
       $categoria = AlpCategorias::find($id);

        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
      

        $imagen='0';

        $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/blog/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

             $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>'0'
            );

        }else{

            $data = array(
                'nombre_categoria' => $request->nombre_categoria, 
                'descripcion_categoria' => $request->descripcion_categoria, 
                'referencia_producto_sap' =>$request->referencia_producto_sap
            );


        }



       
         
       $categoria = AlpCategorias::find($id);
    
        $categoria->update($data);

        if ($categoria->id) {

            return redirect('admin/categorias')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'categorias';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $categoria = AlpCategorias::find($id);

            $confirm_route = route('admin.categorias.delete', ['id' => $categoria->id]);

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
           
            $categoria = AlpCategorias::find($id);


            // Delete the group
            $categoria->delete();

            // Redirect to the group management page
            return Redirect::route('admin.categorias.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.categorias.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function detalle($id)
    {
       
       $categoria = AlpCategorias::find($id);

      

    $categorias = AlpCategorias::select('alp_categorias.*')
        ->where('alp_categorias.id_categoria_parent',$id)->get(); 



        return view('admin.categorias.detalle', compact('categoria', 'categorias'));

    }

    public function storeson(Request $request, $padre)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

          $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/blog/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

        }

        $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>$padre, 
            'id_user' =>$user_id
        );
         
        $categoria=AlpCategorias::create($data);

        if ($categoria->id) {

            return redirect('admin/categorias/'.$padre.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias/'.$padre.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function editson($id)
    {
       
       $categoria = AlpCategorias::find($id);

        return view('admin.categorias.editson', compact('categoria'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function updson(Request $request, $id)
    {
       
         $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/blog/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

            $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>$request->id_categoria_parent
                );

        }else{

                $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'id_categoria_parent' =>$request->id_categoria_parent
                );

        }


       $categoria = AlpCategorias::find($id);
    
        $categoria->update($data);

        if ($categoria->id) {

            return redirect('admin/categorias/'.$request->id_categoria_parent.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias/'.$request->id_categoria_parent.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

}
