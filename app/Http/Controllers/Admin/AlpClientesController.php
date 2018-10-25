<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use Intervention\Image\Facades\Image;
use DOMDocument;
use App\Http\Requests\ClientesRequest;
use App\Mail\Register;
use App\Mail\Restore;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Models\AlpClientes;
use App\Models\AlpDirecciones;
use App\Models\AlpTDocumento;
use App\Models\AlpEmpresas;
use App\User;
use App\RoleUser;
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
      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)->get();


        // Show the page
        return view('admin.clientes.index', compact('clientes'));
    }

    public function inactivos()
    {
        // Grab all the groups
      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.estado_masterfile', '=', 0)
        ->get();


        // Show the page
        return view('admin.clientes.inactivos', compact('clientes'));
    }

    public function rechazados()
    {
        // Grab all the groups
      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.deleted_at', '<>', NULL)
        ->get();


        // Show the page
        return view('admin.clientes.rechazados', compact('clientes'));
    }

    public function empresas()
    {
        // Grab all the groups
      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_empresas.nombre_empresa as nombre_empresa')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('alp_empresas', 'alp_clientes.id_empresa', '=', 'alp_empresas.id')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)->get();


        // Show the page
        return view('admin.clientes.empresas', compact('clientes'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Get all the available groups
        $groups = DB::table('roles')->whereIn('roles.id', [9, 10, 11])->get();

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
                'habeas_cliente' => 1,
                'estado_masterfile' =>0,
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
        $user = User::findOrFail($id);

        $cliente = DB::table('alp_clientes')->where('alp_clientes.id_user_client', '=', $id)->get();

        // Get this user groups
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();
        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->all();

        $status = Activation::completed($user);

        // Get all the available groups
        $groups = DB::table('roles')->where('roles.id', '<>', 1)->get();

        $tdocumento = AlpTDocumento::select(
            DB::raw("CONCAT(nombre_tipo_documento,' - ', abrev_tipo_documento) AS nombre_tipo_documento"),'id')
            ->pluck('nombre_tipo_documento', 'id');

        return view('admin.clientes.edit', compact('groups','tdocumento','user','cliente','userRoles','roles','status'));
    }



    /**
     * Cliente update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    /*public function update(User $id, ClientesRequest $request)
    {
        $user_id = Sentinel::getUser()->id;

        try {


            $data = array(
            
                'id_user_client' => $id, 
                'id_type_doc' => $request->id_type_doc, 
                'doc_cliente' =>$request->doc_cliente, 
                'genero_cliente' =>$request->genero_cliente, 
                'telefono_cliente' =>$request->telefono_cliente, 
                'marketing_cliente' =>$request->marketing_cliente,
                'habeas_cliente' => 1,
                'estado_masterfile' =>$request->activate,
                'id_user' =>$user_id,  
            );
            
            $cliente = AlpClientes::findOrFail($request->id_user_client);

            dd($cliente);
            $cliente->update($data);
    
            $eluser = User::findOrFail($id);
    
            $eluser->update([
                'first_name' => $request->first_name, 
                'last_name' =>$request->last_name, 
                'dob' =>$request->dob, 
                'pic' =>$request->pic
            ]);

        
            if ( !empty($request->password)) {
                $user->password = Hash::make($request->password);

                $data_upd = array(
                    'password' => $user->password, 
                );

                //actualizar password
                $eluser->update($data_upd);

            }

            // is new image uploaded?
            if ($file = $request->file('pic_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/perfiles/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists($destinationPath . $user->pic)) {
                    File::delete($destinationPath . $user->pic);
                }
                //save new file path into db
                $user->pic = $safeName;
                $data_upd_pic = array(
                    'pic' => $user->pic, 
                );

                $eluser->update($data_upd_pic);
            }

            // Get the current user groups
            $userRoles = $eluser->roles()->pluck('id')->all();

            // Get the selected groups

            $selectedRoles = $request->get('groups');

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            $rolesToAdd = array_diff($selectedRoles, $userRoles);
            $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups

            foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($eluser);
            }

            // Remove the user from groups
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->detach($eluser);
            }

            // Activate / De-activate user

            $status = $activation = Activation::completed($eluser);

            if ($request->get('activate') != $status) {
                if ($request->get('activate')) {
                    $activation = Activation::exists($eluser);
                    if ($activation) {
                        Activation::complete($user, $activation->code);
                    }
                } else {
                    //remove existing activation record
                    Activation::remove($eluser);
                    //add new record
                    Activation::create($eluser);
                    //send activation mail
                    $data=[
                        'user_name' =>$eluser->first_name .' '. $eluser->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::exists($eluser)->code])
                    ];
                    // Send the activation code through email
                   /* Mail::to($eluser->email)
                        ->send(new Restore($data));

                }
            }

            // Was the user updated?
            if ($eluser->save()) {
                // Prepare the success message
                $success = trans('users/message.success.update');
               //Activity log for user update
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('Actualizado por '.Sentinel::getUser()->full_name);
                // Redirect to the user page
                return Redirect::route('admin.clientes.edit', $user)->with('success', $success);
            }

            // Prepare the error message
            $error = trans('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.clientes.index')->with('error', $error);
        }

        // Redirect to the user page
        //return Redirect::route('admin.clientes.show', $user)->withInput()->with('error', $error);
    }*/

    public function update(User $user, ClientesRequest $request)
    {

        $user_id = Sentinel::getUser()->id;

        try {

            $user=User::where('id', $request->id_cliente)->first();

            $data_user = array(
                'first_name' => $request->first_name, 
                'last_name' => $request->last_name, 
                'dob' => $request->dob, 
            );
            
            $user->update($data_user);

            if ( !empty($request->password)) {

                $data_user = array(
                'password' => Hash::make($request->password)                 
                );
                
                $user->update($data_user);
            }

            // is new image uploaded?
            if ($file = $request->file('pic_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/perfiles/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists($destinationPath . $user->pic)) {
                    File::delete($destinationPath . $user->pic);
                }
                //save new file path into db
                $pic = $safeName;

                 $data_user = array(
                'pic' => $safeName                 
                );
                
                $user->update($data_user);
            }


            $cliente=AlpClientes::where('id_user_client', $user->id)->first();

            $data_cliente = array(
                'genero_cliente' => $request->genero_cliente, 
                'id_type_doc' => $request->id_type_doc, 
                'doc_cliente' => $request->doc_cliente, 
                'telefono_cliente' => $request->telefono_cliente,
                'marketing_cliente' =>$request->marketing_cliente,
                'estado_masterfile' =>$request->activate,
                'id_user' =>$user_id, 
            );

            $cliente->update($data_cliente);

            //save record
           
            // Get the current user groups
            $userRoles = $user->roles()->pluck('id')->all();


            // Get the selected groups

           // $selectedRoles = $request->get('groups');

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
           // $rolesToAdd = array_diff($selectedRoles, $userRoles);
           // $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups

           /* foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($user);
            }

            // Remove the user from groups
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->detach($user);
            }*/


            $roleusuario=RoleUser::where('user_id', $request->id_cliente)->first();

          //  print_r($roleusuario);

           // print_r($request->groups);



            $role = Sentinel::findRoleById($roleusuario->role_id);
                $role->users()->detach($request->id_cliente);

            $role = Sentinel::findRoleById($request->groups);
                $role->users()->attach($request->id_cliente);

            // Activate / De-activate user

            $status = $activation = Activation::completed($user);

            if ($request->get('activate') != $status) {
                if ($request->get('activate')) {
                    $activation = Activation::exists($user);
                    if ($activation) {
                        Activation::complete($user, $activation->code);
                    }
                } else {
                    //remove existing activation record
                    Activation::remove($user);
                    //add new record
                    Activation::create($user);
                    //send activation mail
                    $data=[
                        'user_name' =>$user->first_name .' '. $user->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::exists($user)->code])
                    ];
                    // Send the activation code through email
                    Mail::to($user->email)
                        ->send(new Restore($data));

                }
            }

            // Was the user updated?
            //if ($user->update()) {
                // Prepare the success message
                $success = trans('users/message.success.update');
               //Activity log for user update
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('User Updated by '.Sentinel::getUser()->full_name);
                // Redirect to the user page
                return Redirect::route('admin.clientes.index')->with('success', $success);
            //}

            // Prepare the error message
            $error = trans('users/message.error.update');

        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.clientes.index')->with('error', $error);
        }

        // Redirect to the user page
        return Redirect::route('admin.clientes.edit', $request->id_cliente)->withInput()->with('error', $error);
    }

    /**
     * Display specified user profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            // Get the user information
            $user = Sentinel::findUserById($id);
            //get country name
            if ($user->country) {
                $user->country = $this->countries[$user->country];
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            // Redirect to the user management page
            return Redirect::route('admin.clientes.index')->with('error', $error);
        }
        // Show the page
        return view('admin.clientes.show', compact('user'));

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


    public function direcciones($id)
    {
        $user = User::findOrFail($id);

        $cliente = DB::table('alp_clientes')->where('alp_clientes.id_user_client', '=', $id)->get();


        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $id)->get();


        return view('admin.clientes.direcciones', compact('user','cliente','direcciones'));
    }


    public function rechazar(Request $request)
    {
        $user_id = Sentinel::getUser()->id;

       try {


            $user=User::where('id', $request->cliente_id)->first();
            
            Activation::remove($user);
                        //add new record
            Activation::create($user);


            $data = array(
                'nota' => $request->notas
                 );

            $cliente=AlpClientes::where('id_user_client', $request->cliente_id)->first();

            $cliente->update($data);


            $cliente->delete();

            //$user->delete();

            return 'true';
            
        } catch (Exception $e) {

            return 'false';
            
        }

        

        
          
    }


    public function activar(Request $request)
    {
        $user_id = Sentinel::getUser()->id;

        $user=User::where('id', $request->cliente_id)->first();


          Activation::remove($user);
                        //add new record
            Activation::create($user);

        $activation = Activation::exists($user);

        if ($activation) {

            Activation::complete($user, $activation->code);

        }

        $data = array(
            'estado_masterfile' => 1,
            'cod_oracle_cliente' => $request->cod_oracle_cliente
             );

        $cliente=AlpClientes::where('id_user_client', $request->cliente_id)->first();

        $cliente->update($data);

         $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('users.id', '=', $request->cliente_id)
        ->first();

        $view= View::make('admin.clientes.trcliente', compact('clientes'));

        $data=$view->render();

        return $data;
          
    }

}
