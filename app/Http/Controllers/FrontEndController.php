<?php

namespace App\Http\Controllers;

use Activation;
use App\Http\Requests\FrontendRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserRequest;
use App\Mail\Contact;
use App\Mail\ContactUser;
use App\Mail\ForgotPassword;
use App\Mail\Register;
use App\Models\AlpClientes;
use App\Models\AlpCategorias;
use App\Models\AlpEmpresas;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpProductos;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpDirecciones;
use App\Models\AlpCodAlpinistas;
use App\User;
use App\State;
use App\RoleUser;
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


class FrontEndController extends JoshController
{
    public function home()
    {
        $descuento='1'; 

        $precio = array();

        $categorias = DB::table('alp_categorias')->select('alp_categorias.*')->where('destacado','=', 1)->where('alp_categorias.estado_registro','=',1)->orderBy('order', 'asc')->limit(9)->get();

        $productos = DB::table('alp_productos')->select('alp_productos.*')->where('destacado','=', 1)->where('alp_productos.estado_registro','=',1)->orderBy('order', 'asc')->limit(8)->get();

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

            if ($role->role_id) {

               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }
                
            }

        }

         $states=State::where('config_states.country_id', '47')->get();
       
        return view('index',compact('categorias','productos', 'descuento', 'precio', 'states'));
        
    }
    /*
     * $user_activation set to false makes the user activation via user registered email
     * and set to true makes user activated while creation
     */
    private $user_activation = false;

    /**
     * Account sign in.
     *
     * @return View
     */
    public function getLogin()
    {
        // Is the user logged in?
        if (Sentinel::check()) {
            
            return Redirect::route('clientes');
        }
        // Show the login page
        return view('login');
    }

    /**
     * Account sign in form processing.
     *
     * @return Redirect
     */
    public function postLogin(Request $request)
    {

        try {
            // Try to log the user in
            if ($user=  Sentinel::authenticate($request->only('email', 'password'), $request->get('remember-me', 0))) {
                //Activity log for login
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('LoggedIn');

                if ($request->back=='0') {
 
                   
                    return Redirect::route("clientes")->with('success', trans('auth/message.login.success'));
                   
                }else{

                    return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));

                }

                
            } else {
                return redirect('login')->with('error', 'Email or password is incorrect.');
                //return Redirect::back()->withInput()->withErrors($validator);
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
        $countries = $this->countries;
        return view('user_account', compact('user', 'countries'));
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
        //update values
        $user->update($request->except('password','pic','password_confirm'));

        if ($password = $request->get('password')) {
            $user->password = Hash::make($password);
        }
        // is new image uploaded?
        if ($file = $request->file('pic')) {
            $extension = $file->extension()?: 'png';
            $folderName = '/uploads/users/';
            $destinationPath = public_path() . $folderName;
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);

            //delete old pic if exists
            if (File::exists(public_path() . $folderName . $user->pic)) {
                File::delete(public_path() . $folderName . $user->pic);
            }
            //save new file path into db
            $user->pic = $safeName;

        }

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
        $states=State::where('config_states.country_id', '47')->get();
        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();
        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

        // Show the page
        return view('register', compact('states','t_documento','estructura'));
    }

    /**
     * Account sign up form processing.
     *
     * @return Redirect
     */
    public function postRegister(UserRequest $request)
    {

        //$activate = $this->user_activation; //make it false if you don't want to activate user automatically it is declared above as global variable
        try {

            if($request->chkalpinista == 1) {

                $codalpin = AlpCodAlpinistas::where('documento_alpi', $request->doc_cliente)->where('codigo_alpi', $request->cod_alpinista)->where('estatus_alpinista',1)->first();
                if ($codalpin) {
                    $activate=true;
                }else{
                    $activate=false;
                }

            }else{
                $activate=false;
            }

            // Register the user
            $user = Sentinel::register($request->only(['first_name', 'last_name', 'email', 'password']), $activate);

            //crear registro en la tabla clientes 

           /* if ($request->gender=='male') {
               $genero=2;
           }else{
                $genero=1;
           }*/

           if($request->chkalpinista == 1) {

                if ($codalpin) {
                    $masterfi=1;
                }else{
                    $masterfi=0;
                }

                $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => $request->habeas_cliente,
                    'marketing_cliente' => $request->marketing_cliente,
                    'estado_masterfile' =>$masterfi,
                    'cod_alpinista'=> $request->cod_alpinista,
                    'id_empresa' =>'0',               
                    'id_embajador' =>'0',               
                    'id_user' =>0,               
                );
            }else{

                $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => $request->habeas_cliente,
                    'marketing_cliente' => $request->marketing_cliente,
                    'estado_masterfile' =>0,
                    'id_empresa' =>'0',               
                    'id_embajador' =>'0',               
                    'id_user' =>0,               
                );
                
            }

            AlpClientes::create($data);

            $direccion = array(
                'id_client' => $user->id, 
                'city_id' => $request->city_id, 
                'id_estructura_address' => $request->id_estructura_address, 
                'principal_address' => $request->principal_address,
                'secundaria_address' => $request->secundaria_address,
                'edificio_address' => $request->edificio_address,
                'detalle_address' => $request->detalle_address,
                'barrio_address'=> $request->barrio_address,             
                'id_user' => 0,               
            );

            AlpDirecciones::create($direccion);

            if($request->chkalpinista == 1) {
                
                if ($codalpin) {

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

                    //add user to 'Embajador' group
                    $role = Sentinel::findRoleById(10);

                    $role->users()->attach($user);
                    
                    $activation = Activation::exists($user);

                    if ($activation) {

                        Activation::complete($user, $activation->code);

                    }
                }
            }else{

                //add user to 'Cliente' group
                $role = Sentinel::findRoleById(9);

                $role->users()->attach($user);

            }



            //if you set $activate=false above then user will receive an activation mail
            if (!$activate) {
                // Data to be used on the email view

                $data=[
                    'user_name' => $user->first_name .' '. $user->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),
                ];
                // Send the activation code through email
                //Mail::to($user->email)->send(new Register($data));
                //Redirect to login page
                return redirect('login')->with('success', trans('auth/message.signup.success'));
            }
            // login user automatically
            Sentinel::login($user, false);
            //Activity log for new account
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Nueva Cuenta Creada');
            // Redirect to the home page with success menu
            return Redirect::route("clientes")->with('success', trans('auth/message.signup.success'));

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    public function postRegisterOld(UserRequest $request)
    {

        $activate = $this->user_activation; //make it false if you don't want to activate user automatically it is declared above as global variable
        try {
            // Register the user
            $user = Sentinel::register($request->only(['first_name', 'last_name', 'email', 'password', 'gender']), $activate);
            //add user to 'User' group
            $role = Sentinel::findRoleByName('User');
            
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
     * User account activation page.
     *
     * @param number $userId
     * @param string $activationCode
     *
     */
    public function getActivate($userId, $activationCode)
    {
        // Is the user logged in?
        if (Sentinel::check()) {
            return Redirect::route('clientes');
        }

        $user = Sentinel::findById($userId);

        if (Activation::complete($user, $activationCode)) {
            // Activation was successfull
            return Redirect::route('login')->with('success', trans('auth/message.activate.success'));
        } else {
            // Activation not found or not completed.
            $error = trans('auth/message.activate.error');
            return Redirect::route('login')->with('error', $error);
        }
    }

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

        try {
            // Get the user password recovery code
            $user = Sentinel::findByCredentials(['email' => $request->email]);
            if (!$user) {
                return Redirect::route('forgot-password')->with('error', trans('auth/message.account_email_not_found'));
            }

            $activation = Activation::completed($user);
            if (!$activation) {
                return Redirect::route('forgot-password')->with('error', trans('auth/message.account_not_activated'));
            }

            $reminder = Reminder::exists($user) ?: Reminder::create($user);
            // Data to be used on the email view

            $data=[
                'user_name' => $user->first_name .' '. $user->last_name,
                'forgotPasswordUrl' => URL::route('forgot-password-confirm', [$user->id, $reminder->code])
            ];
            // Send the activation code through email
            Mail::to($user->email)
                ->send(new ForgotPassword($data));

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
            if($passwordResetCode == $reminder->code)
            {
                return view('forgotpwd-confirm', compact(['userId', 'passwordResetCode']));
            }
            else{
                return 'code does not match';
            }
        }
        else
        {
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
            //Activity log
            $user = Sentinel::getuser();
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('LoggedOut');
            // Log the user out
            Sentinel::logout();

            // Redirect to the users page
            return redirect('login')->with('success', 'You have successfully logged out!');
        } else {

            // Redirect to the users page
            return redirect('admin/signin')->with('error', 'You must be login!');
        }

    }

    public function postRegisterEmbajador(UserRequest $request)
    {

        /*$input=$request->all();

        print_r($input);*/

        $activate = $this->user_activation; //make it false if you don't want to activate user automatically it is declared above as global variable
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
        $cities = DB::table("config_cities")
                    ->where("state_id",$id)
                    ->pluck("city_name","id")->all();
        $states['0'] = 'Seleccione Ciudad';
        return json_encode($cities);
    }


}
