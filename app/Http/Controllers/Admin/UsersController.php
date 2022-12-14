<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\UsuarioRequest;
use App\Mail\Register;
use App\Mail\Restore;
use App\Models\AlpAlmacenes;
use App\User;
use App\State;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use File;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Sentinel;
use URL;
use DB;
use Validator;
use View;
use Yajra\DataTables\DataTables;
use App\Imports\BucaramangaImport;
use App\Imports\SaldoImport;
use Maatwebsite\Excel\Facades\Excel;




class UsersController extends JoshController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index()
    {

        if (!Sentinel::getUser()->hasAnyAccess(['users.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }
       

//        $user = Sentinel::findUserById(2);
//        $activation = Activation::completed($user);
//        return dd($activation);

        $users = '';

        //dd($users);

        // Show the page
        return view('admin.users.index', compact('users'));
    }

    public function nofound()
    {
       

         $states=State::where('config_states.country_id', '47')->get();

         dd($states);

       
        return view('admin/404',compact('states'));

    }

    public function error()
    {
       

         $states=State::where('config_states.country_id', '47')->get();

         dd($states);
         

       
        return view('admin/500',compact('states'));

    }

    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function data()
    {
        //$users = User::get(['id', 'first_name', 'last_name', 'email','created_at']);

        $users =  User::select('users.*')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->whereIn('role_users.role_id', [1, 2, 3, 4, 5, 6, 7, 8,13, 15])->get();

        return DataTables::of($users)
            ->editColumn('created_at',function(User $user) {
                return date("d/m/Y H:i:s", strtotime($user->created_at));
            })
            ->addColumn('status',function($user){

                if($activation = Activation::completed($user)){

                    return 'Activated';} else
                    return 'Pending';

            })
            ->addColumn('actions',function($user) {
                $actions = '<a href='. secure_url('admin/users/'.$user->id).'><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view user"></i></a>
                            <a href='. secure_url('admin/users/'.$user->id.'/edit').'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update user"></i></a>';
                if ((Sentinel::getUser()->id != $user->id) && ($user->id != 1)) {
                    $actions .= '<a href='. secure_url('admin/users/'.$user->id.'/confirm-delete') .' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete user"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function create()
    {

        if (!Sentinel::getUser()->hasAnyAccess(['users.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        // Get all the available groups
        $groups = Sentinel::getRoleRepository()->all();

        $groups = DB::table('roles')->whereIn('roles.id', [1,2,3,4,5,6,7,8,13,15])->get();

        $countries = $this->countries;

        $almacenes=AlpAlmacenes::where('estado_registro', '1')->get();

      //  $almacenes[0]='No Aplica';

        return view('admin.users.create', compact('groups', 'countries', 'almacenes'));

    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(UsuarioRequest $request)
    {

        $input=$request->all();


        //upload image
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }
        //check whether use should be activated by default or not
        $activate = $request->get('activate') ? true : false;

        try {

            $input=$request->all();

            if ($input['almacen']==null || $input['almacen']=='') {

                $request->almacen=0;

               // dd($request->almacen);
            }

          //  dd($request->all());

            // Register the user
            $user = Sentinel::register($request->except('_token', 'password_confirm', 'group', 'activate', 'pic_file'), $activate);

            $data_user = array('token'=>md5(time()) );

            $user->update($data_user);

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
                    'activationUrl' => secure_url('activate', [$user->id, Activation::create($user)->code])
                ];
                // Send the activation code through email
                Mail::to($user->email)
                    ->send(new Register($data));
            }
            // Activity log for New user create
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('New User Created by '.Sentinel::getUser()->full_name);
            // Redirect to the home page with success menu
            return redirect('admin.users.index')->with('success', trans('users/message.success.create'));

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
     * User update.
     *
     * @param  int $id
     * @return View
     */
    public function edit(User $user)
    {

        if (!Sentinel::getUser()->hasAnyAccess(['users.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        // Get this user groups
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();
        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->all();

        $status = Activation::completed($user);

        $countries = $this->countries;

        $almacenes=AlpAlmacenes::where('estado_registro', '1')->get();

        // Show the page
        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'countries', 'status', 'almacenes'));
    }

    /**
     * User update form processing page.
     *
     * @param  User $user
     * @param UsuarioRequest $request
     * @return Redirect
     */
    public function update(User $user, UsuarioRequest $request)
    {


        try {
            $user->update($request->except('pic_file','password','password_confirm','groups','activate'));

            if ( !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            // is new image uploaded?
            if ($file = $request->file('pic_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/users/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists($destinationPath . $user->pic)) {
                    File::delete($destinationPath . $user->pic);
                }
                //save new file path into db
                $user->pic = $safeName;
            }

            //save record
            $user->save();

            // Get the current user groups
            $userRoles = $user->roles()->pluck('id')->all();

            // Get the selected groups

            $selectedRoles = $request->get('groups');

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            $rolesToAdd = array_diff($selectedRoles, $userRoles);
            $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups

            foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($user);
            }

            // Remove the user from groups
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->detach($user);
            }

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
                    'activationUrl' => URL::secure_url('activate', [$user->id, Activation::exists($user)->code])
                    ];
                    // Send the activation code through email
                    /*Mail::to($user->email)
                        ->send(new Restore($data));*/

                }
            }

            // Was the user updated?
            if ($user->save()) {
                // Prepare the success message
                $success = trans('users/message.success.update');
               //Activity log for user update
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('User Updated by '.Sentinel::getUser()->full_name);
                // Redirect to the user page
                 return redirect('admin/users/' )->with('success', $success);
            }

            // Prepare the error message
            $error = trans('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return redirect('admin.users.index')->with('error', $error);
        }

        // Redirect to the user page
        return redirect('admin.users.edit', $user)->withInput()->with('error', $error);
    }

    /**
     * Show a list of all the deleted users.
     *
     * @return View
     */
    public function getDeletedUsers()
    {
        // Grab deleted users
        $users = User::onlyTrashed()->get();

        // Show the page
        return view('admin.deleted_users', compact('users'));
    }


    /**
     * Delete Confirm
     *
     * @param   int $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $model = 'users';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');

                return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        //$confirm_route = secure_url('admin.users.delete', ['id' => $user->id]);
        $confirm_route = secure_url('admin/users/'.$user->id.'/delete');
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    /**
     * Delete the given user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {
        try {
            // Get user information
            $user = Sentinel::findById($id);
            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('admin/users/message.error.delete');
                // Redirect to the user management page
                return redirect('admin/users')->with('error', $error);
            }
            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            User::destroy($id);
            Activation::where('user_id',$user->id)->delete();
            // Prepare the success message
            $success = trans('users/message.success.delete');
            //Activity log for user delete
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('User deleted by '.Sentinel::getUser()->full_name);
            // Redirect to the user management page
            return redirect('admin/users')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return redirect('admin/users')->with('error', $error);
        }
    }

    /**
     * Restore a deleted user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function getRestore($id)
    {
        try {
            // Get user information
            $user = User::withTrashed()->find($id);
            // Restore the user
            $user->restore();
            // create activation record for user and send mail with activation link
//            $data->user_name = $user->first_name .' '. $user->last_name;
//            $data->activationUrl = URL::route('activate', [$user->id, Activation::create($user)->code]);
            // Send the activation code through email

            $activation = Activation::create($user);

            if ($activation) {
                Activation::complete($user, $activation->code);
            }

//           $data=[
//               'user_name' => $user->first_name .' '. $user->last_name,
//            'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code])
//           ];
//            Mail::to($user->email)
//                ->send(new Restore($data));
//            // Prepare the success message
            $success = trans('users/message.success.restored');
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('User restored by '.Sentinel::getUser()->full_name);
            // Redirect to the user management page
            return redirect('admin.deleted_users')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return redirect('admin.deleted_users')->with('error', $error);
        }
    }

    /**
     * Display specified user profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

        if (!Sentinel::getUser()->hasAnyAccess(['users.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        
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
            return redirect('admin.users.index')->with('error', $error);
        }
        // Show the page
        return view('admin.users.show', compact('user'));

    }

    public function passwordreset( Request $request)
    {
        $id = $request->id;
        $user = Sentinel::findUserById($id);
        $password = $request->get('password');
        $user->password = Hash::make($password);
        $user->save();
    }

    public function lockscreen($id){

        if (Sentinel::check()) {
            $user = Sentinel::findUserById($id);
            return view('admin.lockscreen',compact('user'));
        }
        return view('admin.login');
    }

    public function postLockscreen(Request $request){
        $password = Sentinel::getUser()->password;
        if(Hash::check($request->password,$password)){
            return 'success';
        } else{
            return 'error';
        }
    }







}
