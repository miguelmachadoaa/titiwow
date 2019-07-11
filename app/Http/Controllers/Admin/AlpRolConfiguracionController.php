<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpRolConfiguracion;
use App\Models\AlpFormaspago;
use App\Roles;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpRolConfiguracionController extends JoshController
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
                        ->log('AlpRolConfiguracionController/index ');

        }else{

          activity()
          ->log('AlpRolConfiguracionController/index');


        }


        $roles = DB::table('roles')->select('id', 'name')->get();

        $configuraciones = AlpRolConfiguracion::all();
        
        $data = array( );

        foreach ($configuraciones as $conf) {

            $data[$conf->id_rol]['precio']=$conf->precio;
            $data[$conf->id_rol]['referido']=$conf->referido;
            $data[$conf->id_rol]['empresa']=$conf->empresa;

        }


        // Show the page
        return view('admin.rolconfiguracion.create', compact('roles', 'configuraciones', 'data'));
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
                        ->log('AlpRolConfiguracionController/create ');

        }else{

          activity()
          ->log('AlpRolConfiguracionController/create');


        }

        // Show the page
        return view ('admin.rolpagos.create');
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
                        ->withProperties($request->all())->log('AlpRolConfiguracionController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpRolConfiguracionController/store');


        }

        
        
        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        DB::statement("SET foreign_key_checks=0");
        AlpRolConfiguracion::truncate();
        DB::statement("SET foreign_key_checks=1"); 

        $base = array();       

        foreach ($input as $key => $value) {
            
            if ($key!='_token') {

                $ele=explode('-', $value);

                $base[$ele[0]][$ele[1]]=1;

            }
        }

        $roles = DB::table('roles')->select('id', 'name')->get();
        


        foreach ($roles as $rol ) {
            
                $precio='0';
                $empresa='0';
                $referido='0';
              
                if (isset($base[$rol->id]['precio'])) {
                    $precio=1;
                }
               
                if (isset($base[$rol->id]['empresa'])) {
                    $empresa=1;
                }

                if (isset($base[$rol->id]['referido'])) {
                    $referido=1;
                }

                 $data = array(
                'id_rol' => $rol->id, 
                'precio' => $precio, 
                'empresa' => $empresa, 
                'referido' => $referido, 
                'id_user' =>$user_id
                );


                 AlpRolConfiguracion::create($data);

        }

            return redirect('admin/rolconfiguracion'); 

    }
    

}
