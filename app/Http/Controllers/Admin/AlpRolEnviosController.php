<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpRolenvio;
use App\Models\AlpFormasenvio;
use App\Roles;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpRolEnviosController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpRolEnviosController/index ');

        }else{

          activity()
          ->log('AlpRolEnviosController/index');


        }


      

        $formas = AlpFormasenvio::all();

        
        $roles = DB::table('roles')->select('id', 'name')->get();

        $rolenvios=AlpRolenvio::all();

        $data = array( );

        foreach ($rolenvios as $rp) {

        $data[$rp->id_rol][$rp->id_forma_envio]=1;
            

        }

       
        // Show the page
        return view('admin.rolenvios.create', compact('formas', 'roles', 'data'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpRolEnviosController/index ');

        }else{

          activity()
          ->log('AlpRolEnviosController/index');


        }


        return view ('admin.rolenvios.create');
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
                        ->withProperties($request->all())->log('AlpRolEnviosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpRolEnviosController/store');


        }

        
        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        DB::statement("SET foreign_key_checks=0");
        AlpRolenvio::truncate();
        DB::statement("SET foreign_key_checks=1");        

        foreach ($input as $key => $value) {
            
            if ($key!='_token') {

                $ele=explode('-', $value);

                 $data = array(
                'id_rol' => $ele[0], 
                'id_forma_envio' => $ele[1], 
                'id_user' =>$user_id
                );


                 AlpRolenvio::create($data);

                
            }
        }

            return redirect('admin/rolenvios'); 

    }


}
