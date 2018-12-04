<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\GroupRequest;
use App\Models\AlpModulos;
use Redirect;
use Sentinel;
use View;
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
            'slug' => str_slug($request->get('name'))
        ])
        ) {
            dd($request->all());
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
        
        $routes = Route::getRoutes();

        //Api Route
        // $api = app('api.router');
        // /** @var $api \Dingo\Api\Routing\Router */
        // $routeCollector = $api->getRoutes(config('api.version'));
        // /** @var $routeCollector \FastRoute\RouteCollector */
        // $api_route = $routeCollector->getRoutes();


        $actions = [];
        foreach ($routes as $route) {
            if ($route->getName() != "" && !substr_count($route->getName(), 'payment')) {
                $actions[] = $route->getName();
            }            
        }
        
        //remove store option
        $input = preg_quote("store", '~');
        $var = preg_grep('~' . $input . '~', $actions);
        $actions = array_values(array_diff($actions, $var));

        //remove update option
        $input = preg_quote("update", '~');
        $var = preg_grep('~' . $input . '~', $actions);
        $actions = array_values(array_diff($actions, $var));

        //Api all names
        // foreach ($api_route as $route) {
        //     if ($route->getName() != "" && !substr_count($route->getName(), 'payment')) {
        //         $actions[] = $route->getName();
        //     }            
        // }
        
        $var = [];
        $i = 0;
        foreach ($actions as $action) {

            $input = preg_quote(explode('.', $action )[0].".", '~');
            $var[$i] = preg_grep('~' . $input . '~', $actions);
            $actions = array_values(array_diff($actions, $var[$i]));
            $i += 1;
        }

        $actions = array_filter($var);
        // dd (array_filter($actions));
        return View('admin.groups.permisos', compact('role', 'actions'));
    }

    public function save($id, Request $request){
        $role = Sentinel::findRoleById($id);
        
        $role->permissions = [];
        if($request->permissions){
            foreach ($request->permissions as $permission) {
                if(explode('.', $permission)[1] == 'create'){
                    $role->addPermission($permission);
                    $role->addPermission(explode('.', $permission)[0].".store");                
                }
                else if(explode('.', $permission)[1] == 'edit'){
                    $role->addPermission($permission);
                    $role->addPermission(explode('.', $permission)[0].".update");                
                }
                else{
                    $role->addPermission($permission);
                }            
            }  
        }
        
        $role->save();
        
        Session::flash('message', 'Success! Permissions are stored successfully.');
        Session::flash('status', 'success');
        return redirect('role');
    }





}
