<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\ForgotRequest;
use App\Http\Requests\UserRequest;
use App\Mail\ForgotPassword;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Roles;
use App\RoleUser;
use App\User;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpDirecciones;
use App\Models\AlpEmpresas;
use App\Models\AlpConfiguracion;

use Mail;
use Reminder;
use Sentinel;
use stdClass;
use URL;
use Validator;
use View;


class AuthController extends JoshController
{
    /**
     * Account sign in.
     *
     * @return View
     */
    public function getSignin()
    {

        // Is the user logged in?
        if (Sentinel::check()) {
            return Redirect::route('admin.dashboard');
        }

        // Show the page
        return view('admin.login');
    }

    /**
     * Account sign in form processing.
     * @param Request $request
     * @return Redirect
     */
    public function postSignin(Request $request)
    {

        try {
            // Try to log the user in
            if ($user = Sentinel::authenticate($request->only(['email', 'password']), $request->get('remember-me', false))) {
                // Redirect to the dashboard page
                //Activity log
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('LoggedIn');
                //activity log ends

                //se recupera el rol 

                $user_id = Sentinel::getUser()->id;


                if ($request->back=='0') {
 
                    return Redirect::route("admin.dashboard")->with('success', trans('auth/message.signin.success'));
                   
                }else{

                    return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));

                }
            }

            $this->messageBag->add('email', trans('auth/message.account_not_found'));

        } catch (NotActivatedException $e) {
            $this->messageBag->add('email', trans('auth/message.account_not_activated'));
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $this->messageBag->add('email', trans('auth/message.account_suspended', compact('delay')));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    /**
     * Account sign up form processing.
     *
     * @return Redirect
     */
    public function postSignup(UserRequest $request)
    {


         $configuracion=AlpConfiguracion::where('id', '1')->first();



         if($configuracion->user_activacion==0){

            $activate=true;

            $masterfi=1;

         }else{

            $activate=false;

            $masterfi=0;

         }



        try {
            // Register the user
            $user = Sentinel::registerAndActivate([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ], $activate);

            //add user to 'User' group
            $role = Sentinel::findRoleById(2);
            $role->users()->attach($user);


            // Log the user in
            $name = Sentinel::login($user, false);
            //Activity log

            activity($name->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Registered');
            //activity log ends
            // Redirect to the home page with success menu
           // return Redirect::route("admin.dashboard")->with('success', trans('auth/message.signup.success'));

                 $mensaje='Gracias por registrarte en AlpinaGo, Ya puedes disfrutar de nuestros productos con un descuento especial.';

             Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida));

             if ($request->back=='0') {

                    return Redirect::route("admin.dashboard")->with('success', trans('auth/message.signin.success'));
                   
                }else{

                    return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));
                    
                }

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
     * @return
     */
    public function getActivate($userId,$activationCode = null)
    {
        // Is user logged in?
        if (Sentinel::check()) {
            return Redirect::route('admin.dashboard');
        }

        $user = Sentinel::findById($userId);
        $activation = Activation::create($user);

        if (Activation::complete($user, $activation->code)) {
            // Activation was successful
            // Redirect to the login page
            return Redirect::route('signin')->with('success', trans('auth/message.activate.success'));
        } else {
            // Activation not found or not completed.
            $error = trans('auth/message.activate.error');
            return Redirect::route('signin')->with('error', $error);
        }

    }

    /**
     * Forgot password form processing page.
     * @param Request $request
     *
     * @return Redirect
     */
    public function postForgotPassword(ForgotRequest $request)
    {
        $data = new stdClass();

        try {
            // Get the user password recovery code
            $user = Sentinel::findByCredentials(['email' => $request->get('email')]);

            if (!$user) {
                return back()->with('error', trans('auth/message.account_email_not_found'));
            }

            $activation = Activation::completed($user);

            if(!$activation){

                return back()->with('error', trans('auth/message.account_not_activated'));

            }

            $reminder = Reminder::exists($user) ?: Reminder::create($user);
            // Data to be used on the email view

           // $data->user_name = $user->first_name .' ' .$user->last_name;

           // $data->forgotPasswordUrl = secure_url::route('forgot-password-confirm', [$user->id, $reminder->code]);


            $data=[
                'user_name' => $user->first_name .' '. $user->last_name,
                'forgotPasswordUrl' => URL::route('forgot-password-confirm', [$user->id, $reminder->code])
            ];

            // Send the activation code through email

            Mail::to($user->email)
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
     * @param number $userId
     * @param  string $passwordResetCode
     * @return View
     */
    public function getForgotPasswordConfirm($userId,$passwordResetCode = null)
    {
        // Find the user using the password reset code
        if(!$user = Sentinel::findById($userId)) {
            // Redirect to the forgot password page
            return Redirect::route('forgot-password')->with('error', trans('auth/message.account_not_found'));
        }
        if($reminder = Reminder::exists($user)) {
            if($passwordResetCode == $reminder->code) {
                return view('admin.auth.forgot-password-confirm', compact(['userId', 'passwordResetCode']));
            } else{
                return 'code does not match';
            }
        } else {
            return 'does not exists';
        }

        // Show the page
        // return View('admin.auth.forgot-password-confirm');
    }

    /**
     * Forgot Password Confirmation form processing page.
     *
     * @param Request $request
     * @param number $userId
     * @param  string   $passwordResetCode
     * @return Redirect
     */
    public function postForgotPasswordConfirm(Request $request, $userId, $passwordResetCode = null)
    {

        // Find the user using the password reset code
        $user = Sentinel::findById($userId);

        if (!$reminder = Reminder::complete($user, $passwordResetCode, $request->get('password'))) {
            // Ooops.. something went wrong
            return Redirect::route('signin')->with('error', trans('auth/message.forgot-password-confirm.error'));
        }

        // Password successfully reseted
        return Redirect::route('signin')->with('success', trans('auth/message.forgot-password-confirm.success'));
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
            return redirect('admin/signin')->with('success', 'Cerró Sesión Exitosamente');
        } else {

        // Redirect to the users page
            return redirect('admin/signin')->with('error', 'Debes Iniciar Sesión');
        }
    }

    /**
     * Account sign up form processing for register2 page
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postRegister2(UserRequest $request)
    {

        try {
            // Register the user
            $user = Sentinel::registerAndActivate(array(
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ));

            //add user to 'User' group
            $role = Sentinel::findRoleById(2);
            $role->users()->attach($user);

            // Log the user in
            Sentinel::login($user, false);

           


            // Redirect to the home page with success menu
            return Redirect::route("admin.dashboard")->with('success', trans('auth/message.signup.success'));

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    /**
     * Account sign up form processing.
     *
     * @return Redirect
     */
    public function postSignupEmbajador(UserRequest $request)
    {


        $configuracion=AlpConfiguracion::where('id', '1')->first();



         if($configuracion->user_activacion==0){

            $activate=true;

            $masterfi=1;

         }else{

            $activate=false;

            $masterfi=0;

         }




        $input=$request->all();
        

        $amigo=AlpAmigos::where('token', $request->referido)->first();


        if (isset($amigo->id)) {
            # code...
        

        try {

            $user = Sentinel::register([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ], $activate);

            //add user to 'User' group
            $role = Sentinel::findRoleById(11);
            $role->users()->attach($user);


             $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => $request->habeas_cliente,
                    'marketing_cliente' => $request->marketing_cliente,
                    'estado_masterfile' =>0,
                    'id_empresa' =>'0',               
                    'id_embajador' =>$amigo->id_cliente,               
                    'id_user' =>$user->id,               
                );

             $embajador=User::where('id',$amigo->id_cliente )->first();




           $cliente= AlpClientes::create($data);

             $direccion = array(
                'id_client' => $user->id, 
                'city_id' => $request->city_id, 
                'id_estructura_address' => $request->id_estructura_address, 
                'principal_address' => $request->principal_address,
                'secundaria_address' => $request->secundaria_address,
                'edificio_address' => $request->edificio_address,
                'detalle_address' => $request->detalle_address,
                'barrio_address'=> $request->barrio_address,             
                'id_user' => $user->id,               
            );

            AlpDirecciones::create($direccion);


            $amigo->delete();




            if ($activate) {

                  $name = Sentinel::login($user, false);
            //Activity log

                activity($name->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Registered');



                  $data_c = array(
                    'estado_masterfile' =>$request->telefono_cliente
                );


            $cliente->update($data_c);


                $mensaje='Gracias por registrarte en AlpinaGo, Ya puedes disfrutar de nuestros productos con un descuento especial.';


                 Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida));

                  Mail::to($embajador->email)->send(new \App\Mail\AmigoRegistrado($user->first_name, $user->last_name));



                //return Redirect::route("clientes")->with('success', trans('auth/message.signup.success'));
                return Redirect::route("home")->with('success', trans('auth/message.signup.success'));


            }else{

                $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';

                 Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida));

                  Mail::to($embajador->email)->send(new \App\Mail\AmigoRegistrado($user->first_name, $user->last_name));


            }


            

            //activity log ends
            // Redirect to the home page with success menu

            if ($request->back=='0') {


                 $mensaje="Ha sido registrado satisfactoriamente como Amigo Alpina, debe esperar que su Usuario sea activado en un proceso interno, le notificaremos vía email";

                return view('frontend.clientes.aviso',  compact('mensaje'));

                   
              }else{

                    return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));
                    
                }

        } catch (UserExistsException $e) {

            $this->messageBag->add('email', trans('auth/message.account_already_exists'));

        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);

        }else{

            

            return Redirect::back()->withInput()->withErrors('Su enlace ha vencido, solicite uno nuevo o registrese como cliente ');


        }
    }

    public function postSignupAfiliado(UserRequest $request)
    {


        $configuracion=AlpConfiguracion::where('id', '1')->first();



         if($configuracion->user_activacion==0){

            $activate=true;

            $masterfi=1;

         }else{

            $activate=false;

            $masterfi=0;

         }




        $input=$request->all();

        //print_r($input);

         $amigo=AlpAmigos::where('token', $request->empresa)->first();



        //print_r($amigo);

        if (isset($amigo->id)) {


            $id_empresa=substr($amigo->id_cliente, 1);

            $empresa=AlpEmpresas::where('id', $id_empresa)->first();


        try {
            // Register the user
            $user = Sentinel::register([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ], $activate);



            //add user to 'User' group
            $role = Sentinel::findRoleById(12);
            $role->users()->attach($user);

              $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => $request->habeas_cliente,
                    'marketing_cliente' => $request->marketing_cliente,
                    'estado_masterfile' =>0,
                    'id_empresa' =>substr($amigo->id_cliente, 1),               
                    'id_embajador' =>'0',               
                    'id_user' =>$user->id,               
                );

             //print_r($data);

            $cliente=AlpClientes::create($data);

             $direccion = array(
                'id_client' => $user->id, 
                'city_id' => $request->city_id, 
                'id_estructura_address' => $request->id_estructura_address, 
                'principal_address' => $request->principal_address,
                'secundaria_address' => $request->secundaria_address,
                'edificio_address' => $request->edificio_address,
                'detalle_address' => $request->detalle_address,
                'barrio_address'=> $request->barrio_address,             
                'id_user' => $user->id,               
            );

            AlpDirecciones::create($direccion);

             $amigo->delete();


             if ($activate) {

                  $name = Sentinel::login($user, false);
            //Activity log

                activity($name->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Registered');


                  $data_c = array(
                    'estado_masterfile' =>$request->telefono_cliente
                );


            $cliente->update($data_c);


                $mensaje='Gracias por registrarte en AlpinaGo, Ya puedes disfrutar de nuestros productos con un descuento especial.';


                 Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida));



               // return Redirect::route("clientes")->with('success', trans('auth/message.signup.success'));
                return Redirect::route("home")->with('success', trans('auth/message.signup.success'));


            }else{

                $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';

                 Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida));


            }

           

            if ($request->back=='0') {

                //$mensaje="Ha sido registrado satisfactoriamente como Amigo Alpina, debe esperar que su Usuario sea activado en un proceso interno, le notificaremos vía email";

                $mensaje="Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa.", debe esperar que su Usuario sea activado en un proceso interno, te notificaremos vía email su activación.";

                return view('frontend.clientes.aviso_empresa',  compact('mensaje'));
                   
                }else{

                    return Redirect::route($request->back)->with('success', trans('auth/message.signin.success'));
                    
                }

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);

         }else{

            
        return Redirect::back()->withInput()->withErrors($this->messageBag);



        }
    }


}
