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


  public function ibm()
  {


    $pod = 0;
    $username = 'api_alpina@alpina.com';
    $password = 'Alpina2020!';

    $endpoint = "https://api2.ibmmarketingcloud.com/XMLAPI";
    $jsessionid = null;

    $baseXml = '%s';
    $loginXml = '';
    $getListsXml = '%s%s';
    $logoutXml = '';

    try {


    $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

    $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

    print_r($result);

    $jsessionid = $result['SESSIONID'];

    echo $jsessionid.'<br>';

        $xml='
        <Envelope>
           <Body>
              <AddRecipient>
                 <LIST_ID>10491915  </LIST_ID>
                 <CREATED_FROM>1</CREATED_FROM>
                 <COLUMN>
                    <NAME>Customer Id</NAME>
                    <VALUE>1</VALUE>
                 </COLUMN>
                 <COLUMN>
                    <NAME>EMAIL</NAME>
                    <VALUE>mmachado@crearemos.com</VALUE>
                 </COLUMN>
                 <COLUMN>
                    <NAME>Miguel</NAME>
                    <VALUE>Machado</VALUE>
                 </COLUMN>
              </AddRecipient>
           </Body>
        </Envelope>
        ';

    $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

    print_r($result);

    echo "3<br>";

//LOGOUT

    $xml = '<Envelope>
      <Body>
      <Logout/>
      </Body>
      </Envelope>';

          $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

          print_r($result);

          $jsessionid = null;

      } catch (Exception $e) {

          die("\nException caught: {$e->getMessage()}\n\n");

      }

    }


  public function getXml()
    {

      $productos=AlpProductos::select('alp_productos.*', 'alp_marcas.nombre_marca as nombre_marca')
      ->join('alp_marcas', 'alp_productos.id_marca', '=','alp_marcas.id')
      ->where('alp_productos.estado_registro','=',1)
      ->get();

      $inventario=$this->inventario();
        // Is the user logged in?
      return view('frontend.xml', compact('productos', 'inventario'));

    }


    public function getContacto()
    {
        // Is the user logged in?
        return view('frontend.contacto');

    }

    
    public function home()
    {

      $id_almacen=$this->getAlmacen();


        $descuento='1'; 

        $clientIP = \Request::getClientIp(true);

        $precio = array();

        $configuracion=AlpConfiguracion::where('id', '1')->first();

        $categorias = DB::table('alp_categorias')->select('alp_categorias.*')->where('destacado','=', 1)->where('alp_categorias.estado_registro','=',1)->orderBy('order', 'asc')->limit(9)->get();

        $productos = DB::table('alp_productos')->select('alp_productos.*')->where('destacado','=', 1)
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.id', '=', $id_almacen)
        ->whereNull('alp_almacen_producto.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('order', 'asc')
        
        ->orderBy('updated_at', 'desc')
        ->limit(12)->get();

        $marcas = DB::table('alp_marcas')->select('alp_marcas.*')->where('destacado','=', 1)->where('alp_marcas.estado_registro','=',1)->orderBy('order', 'asc')->limit(12)->get();

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $user=Sentinel::getUser();
             
            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                    $role->role_id='E'.$role->role_id.'';
                }
               
            }

            if ($role->role_id) {

               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
            }

        }else{

          $role = array( );

            $r='9';
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
        }

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

       $cart= \Session::get('cart');

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }
      
        $sliders=AlpSliders::orderBy("order")->get();

        $inventario=$this->inventario();

        $combos=$this->combos();

        return view('index',compact('categorias','productos','marcas','descuento','precio', 'cart', 'total','prods','sliders','configuracion','inventario', 'combos', 'role'));

    }

    private $user_activation = false;


    public function getLogin()
    {

        if (Sentinel::check()) {
            
            return Redirect::route('clientes');
        }
        // Show the login page
        return view('login');
    }

     public function desactivado()
    {
        // Is the user logged in?
        
        // Show the login page
        return view('desactivado');
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


                $role = DB::table('role_users')
               ->select('role_users.role_id')
               ->where('user_id','=', $user->id)
               ->first();

               if ( $role->role_id>'8' || $role->role_id=='13') {

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

        $cart= \Session::get('cart');

       
        return view('user_account', compact('user', 'countries', 'cart'));
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

        $configuracion=AlpConfiguracion::where('id', '1')->first();

        if ( $configuracion->registro_publico==0) {
                
                return view('desactivado');
            # code...
        }
        
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

         $configuracion=AlpConfiguracion::where('id', '1')->first();

         $input=$request->all();

         if($configuracion->user_activacion==0){

            $activate=true;

            $masterfi=1;

         }else{

            $activate=false;

            $masterfi=0;

         }

        try {

            if($request->chkalpinista == 1) {

                $codalpin = AlpCodAlpinistas::where('documento_alpi', $request->doc_cliente)->where('codigo_alpi', $request->cod_alpinista)->where('estatus_alpinista',1)->first();

                if ($codalpin) {

                  //  $activate=false;

                    // Register the user
                    $user = Sentinel::register($request->only(['first_name', 'last_name', 'email', 'password']), $activate);



                    $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => $request->habeas_cliente,
                    'estado_masterfile' =>$masterfi,
                    'cod_alpinista'=> $request->cod_alpinista,
                    'cod_oracle_cliente'=> $codalpin->cod_oracle_cliente,
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

                    //add user to 'Embajador' group
                    $role = Sentinel::findRoleById(10);

                    $role->users()->attach($user);
                    
                    $activation = Activation::exists($user);

                    if ($activation) {

                        Activation::complete($user, $activation->code);

                    }

                       $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';


                       $roleusuario=RoleUser::where('user_id', $user->id)->first();

 


                      Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida, $roleusuario ));

                      Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida, $roleusuario ));

                    }else{

                        return redirect('registro')->with('error', trans('auth/message.failure.error'))->withInput();

                    }

            }else{


                  $user = Sentinel::register($request->only(['first_name', 'last_name', 'email', 'password']), $activate);

                  if ($request->convenio!='') {
                    
                    $empresa=AlpEmpresas::where('convenio', $request->convenio)->first();

                    if (isset($empresa->id)) {
                      # code...
                    }else{

                      return redirect('registro')->with('error', trans('El Código de Convenio no existe'))->withInput();

                    }

                    #dd($empresa);


                  }

                  if (isset($empresa->id)) {
                    
                  }else{

                    $dominio=explode('@', $request->email);

                    $empresa=AlpEmpresas::where('dominio',$dominio[1])->first();

                  }


                 // dd($empresa);

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

                     $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';

                    Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida, $roleusuario));

                    Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida, $roleusuario));
            }


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

                if ($id_empresa==0) {
                   
                    return redirect('login')->with('success', trans('auth/message.signup.success'));

                }else{

                    $user_history = array(
                        'id_client' => $user->id,
                        'estatus_cliente' => "Activado",
                        'notas' => "Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa,
                        'id_user'=>$user->id
                         );

                    AlpClientesHistory::create($user_history);


                     $configuracion->mensaje_bienvenida="Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa.", debe esperar que su Usuario sea activado en un proceso interno, te notificaremos vía email su activación.";

                return redirect('login?registro='.$user->id)->with('success', trans($mensaje));

                }

            }

            // login user automatically
            Sentinel::login($user, false);
            //Activity log for new account
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Nueva Cuenta Creada');


           $data_c = array(
                    'cod_oracle_cliente' =>$request->telefono_cliente,
                    'estado_masterfile' =>'1'
                );


            $cliente->update($data_c);











            $pod = 0;
    $username = 'api_alpina@alpina.com';
    $password = 'Alpina2020!';

    $endpoint = "https://api2.ibmmarketingcloud.com/XMLAPI";
    $jsessionid = null;

    $baseXml = '%s';
    $loginXml = '';
    $getListsXml = '%s%s';
    $logoutXml = '';

    try {


        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        print_r($result);

        $jsessionid = $result['SESSIONID'];

        echo $jsessionid.'<br>';

            $xml='
            <Envelope>
               <Body>
                  <AddRecipient>
                     <LIST_ID>10491915  </LIST_ID>
                     <CREATED_FROM>1</CREATED_FROM>
                     <COLUMN>
                        <NAME>Customer Id</NAME>
                        <VALUE>1</VALUE>
                     </COLUMN>
                     <COLUMN>
                        <NAME>EMAIL</NAME>
                        <VALUE>mmachado@crearemos.com</VALUE>
                     </COLUMN>
                     <COLUMN>
                        <NAME>Miguel</NAME>
                        <VALUE>Machado</VALUE>
                     </COLUMN>
                  </AddRecipient>
               </Body>
            </Envelope>
            ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        print_r($result);

        echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

              print_r($result);

              $jsessionid = null;

          } catch (Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

          }














           // return Redirect::route("clientes")->with('success', trans('auth/message.signup.success'));
            return redirect("/?registro=".time())->with('success', trans('Bienvenido a Alpina GO!. Ya puedes comprar todos nuestro productos y promociones. Alpina Alimenta tu vida. '));



        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    public function postRegisterOld(UserRequest $request)
    {

        $activate = false; //$this->user_activation; //make it false if you don't want to activate user automatically it is declared above as global variable
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

       // dd($request);

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

            // Data to be used on the email view

            $data=[
                'user_name' => $user->first_name .' '. $user->last_name,
                'forgotPasswordUrl' => URL::route('olvido-clave-confirm', [$user->id, $reminder->code])
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



        /*$input=$request->all();

        print_r($input);*/

        //$activate = false;//$this->user_activation; //make it false if you don't want to activate user automatically it is declared above as global variable
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
        $cities = DB::table("config_cities")
                    ->where("state_id",$id)
                    ->pluck("city_name","id")->all();
        $states['0'] = 'Seleccione Ciudad';
        return json_encode($cities);
    }


    private function inventario()
    {
       
       $id_almacen=$this->getAlmacen();

      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

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
        ->where('id_combo', $co->id)
        ->get();

        foreach ($lista as $l) {

            if (isset($inventario[$l->id_producto])) {
                
                if($inventario[$l->id_producto]>0){

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
        throw new Exception('CURL error: ' . curl_error($curl));
    }

    curl_close($curl);

    if (true === $response || !trim($response)) {
        throw new Exception('Empty response from WCA');
    }

    $xmlResponse = simplexml_load_string($response);

    if (false === $ignoreResult) {
        if (false === isset($xmlResponse->Body->RESULT)) {
            var_dump($xmlResponse);
            throw new Exception('Unexpected response from WCA');
        }

        return $xmlResponse->Body->RESULT;
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


      if (isset(Sentinel::getUser()->id)) {
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

          //dd($d);


          if (isset($d->id)) {
              
              $almacen=AlpAlmacenes::where('id_city', $d->city_id)->first();

              if (isset($almacen->id)) {
                
                $id_almacen=$almacen->id;

              }else{

                $almacen=AlpAlmacenes::where('defecto', '1')->first();

                $id_almacen=$almacen->id;

              }

          }else{

              $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
            ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
            ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
            ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
            ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
            ->where('alp_direcciones.id_client', $user_id)
            ->first();


            if (isset($d->id)) {
                        
                $almacen=AlpAlmacenes::where('id_city', $d->city_id)->first();

              if (isset($almacen->id)) {
                
                $id_almacen=$almacen->id;

              }else{

                $almacen=AlpAlmacenes::where('defecto', '1')->first();

                $id_almacen=$almacen->id;

              }


            }else{

              $almacen=AlpAlmacenes::where('defecto', '1')->first();

              $id_almacen=$almacen->id;
            }
        }


        }else{

          $almacen=AlpAlmacenes::where('defecto', '1')->first();

          $id_almacen=$almacen->id;
        
      }

      return $id_almacen;

    }









}
    