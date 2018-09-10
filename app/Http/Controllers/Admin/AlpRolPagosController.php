<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpRolpago;
use App\Models\AlpFormaspago;
use App\Roles;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpRolPagosController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $formas = AlpFormaspago::all();

        $roles = Roles::all();

        $rolpagos=AlpRolpago::all();

        $data = array( );

        foreach ($rolpagos as $rp) {

        $data[$rp->id_rol][$rp->id_forma_pago]=1;
            

        }
       
        // Show the page
        return view('admin.rolpagos.create', compact('formas', 'roles', 'data'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
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
        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        DB::statement("SET foreign_key_checks=0");
        AlpRolpago::truncate();
        DB::statement("SET foreign_key_checks=1");        

        foreach ($input as $key => $value) {
            
            if ($key!='_token') {

                $ele=explode('-', $value);

                 $data = array(
                'id_rol' => $ele[0], 
                'id_forma_pago' => $ele[1], 
                'id_user' =>$user_id
                );


                 AlpRolpago::create($data);

                
            }
        }

            return redirect('admin/rolpagos'); 

    }
    

}
