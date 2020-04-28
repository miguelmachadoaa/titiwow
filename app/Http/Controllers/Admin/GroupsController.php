<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\GroupRequest;
use App\Models\AlpModulos;
use Redirect;
use Sentinel;
use View;
use App\Http\Requests;

use Illuminate\Http\Request;
use Route;


class GroupsController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
        $roles = Sentinel::getRoleRepository()->all();

        // Show the page
        return view('admin.groups.index', compact('roles'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        $modulos = AlpModulos::all();
        // Show the page
        return view ('admin.groups.create', compact('modulos'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(GroupRequest $request)
    {
        if ($role = Sentinel::getRoleRepository()->createModel()->create([
            'name' => $request->get('name'),
            'tipo' => $request->get('tipo'),
            'oferta' => $request->get('oferta'),
            'monto_minimo' => $request->get('monto_minimo'),
            'slug' => str_slug($request->get('name'))
        ])
        ) {


        $group = Sentinel::findRoleById($role->id);

        // Update the group data
        $group->name = $request->get('name');
        $group->tipo = $request->get('tipo');
        $group->oferta = $request->get('oferta');
        $group->monto_minimo = $request->get('monto_minimo');
        $group->save();


           // dd($request->all());
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permission_name) {

                }

            } 

            // Redirect to the new group page
            return Redirect::route('admin.groups.index')->with('success', trans('groups/message.success.create'));
        }

        // Redirect to the group create page
        return Redirect::route('admin.groups.create')->withInput()->with('error', trans('groups/message.error.create'));

    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($group)
    {
        try {
            // Get the group information
            $role = Sentinel::findRoleById($group);

        } catch (GroupNotFoundException $e) {
            // Redirect to the groups management page
            return Redirect::route('admin.groups')->with('error', trans('groups/message.group_not_found', compact('id')));
        }

        //dd($role);

        // Show the page
        return view('admin.groups.edit', compact('role'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update($group, GroupRequest $request)
    {
        $group = Sentinel::findRoleById($group);

        // Update the group data
        $group->name = $request->get('name');
        $group->tipo = $request->get('tipo');
        $group->oferta = $request->get('oferta');
        $group->monto_minimo = $request->get('monto_minimo');

        // Was the group updated?
        if ($group->save()) {
            // Redirect to the group page
            return Redirect::route('admin.groups.index')->with('success', trans('groups/message.success.update'));
        } else {
            // Redirect to the group page
            return Redirect::route('admin.groups.edit', $group)->with('error', trans('groups/message.error.update'));
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
        $model = 'groups';
        $confirm_route = $error = null;
        try {
            // Get group information
            $role = Sentinel::findRoleById($id);
            $confirm_route = route('admin.groups.delete', ['id' => $role->id]);
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('admin/groups/message.group_not_found', compact('id'));
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
            $role = Sentinel::findRoleById($id);

            // Delete the group
            $role->delete();

            // Redirect to the group management page
            return Redirect::route('admin.groups.index')->with('success', trans('groups/message.success.delete'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.groups.index')->with('error', trans('groups/message.group_not_found', compact('id')));
        }
    }









     public function permissions($id){
        
        $role = Sentinel::findRoleById($id);

        $acceso=$role->permissions;
        
        $routes = Route::getRoutes();

        //dd($routes);

        $iterator=$routes->getIterator();

        //dd($iterator);

        //dd($routes);

        $listado = array();

        foreach ($routes as $ro) {

            if (substr($ro->getName(), 0,5)=='admin') {

                $lis=explode('.', $ro->getName());

                if(count($lis)>2){

                    $listado[$lis[1]][]=$lis[2];


                }

                
            }
            
            
        }

        //dd($listado);

        //Api Route
        // $api = app('api.router');
        // /** @var $api \Dingo\Api\Routing\Router */
        // $routeCollector = $api->getRoutes(config('api.version'));
        // /** @var $routeCollector \FastRoute\RouteCollector */
        // $api_route = $routeCollector->getRoutes();


     
        return View('admin.groups.permisos', compact('listado', 'role', 'acceso'));
    }

    public function save($id, Request $request){
        $role = Sentinel::findRoleById($id);
        



        $input=$request->all();

       // dd($input);


        $per = array( );

        foreach ($input as $key => $value) {
           
            if ($key!='_token') {
                
               foreach ($value as $key2 => $value2) {
                 
                 $per[$key.'.'.$value2]=true;

               }

            }




        }

        $role->permissions = $per;
        
        
        $role->save();
        
       // Session::flash('message', 'Success! Permissions are stored successfully.');
        //Session::flash('status', 'success');
        return redirect('admin/groups');
    }


 public function guardar($id, Request $request){
        $role = Sentinel::findRoleById($id);
        



        $input=$request->all();

       // dd($input);


        $per = array( );

        foreach ($input as $key => $value) {
           
            if ($key!='_token') {
                
               foreach ($value as $key2 => $value2) {
                 
                 $per[$key.'.'.$value2]=true;

               }

            }




        }

        $role->permissions = $per;
        
        
        $role->save();
        
       // Session::flash('message', 'Success! Permissions are stored successfully.');
        //Session::flash('status', 'success');
        return redirect('admin/groups');
    }





}
