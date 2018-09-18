<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpMenu;
use App\Models\AlpDetallesMenu;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use Intervention\Image\Facades\Image;
use DOMDocument;


class AlpMenuController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $menus = AlpMenu::all();
       

        // Show the page
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.menus.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        
         $user_id = Sentinel::getUser()->id;

 

        
        

        $data = array(
            'nombre_menu' => $request->nombre_menu,
            'id_user' =>$user_id
        );


         
        $menu=AlpMenu::create($data);

        if ($menu->id) {

            return redirect('admin/menus')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        } 

    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
       
       $menu = AlpMenu::find($id);

        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
      

       

            $data = array(
                'nombre_menu' => $request->nombre_menu
            );

       
         
       $menu = AlpMenu::find($id);
    
        $menu->update($data);

        if ($menu->id) {

            return redirect('admin/menus')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'menus';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $menu = AlpMenu::find($id);

            $confirm_route = route('admin.menus.delete', ['id' => $menu->id]);

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
           
            $menu = AlpMenu::find($id);


            // Delete the group
            $menu->delete();

            // Redirect to the group management page
            return Redirect::route('admin.menus.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.menus.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function detalle($id)
    {
       
       $menu = AlpMenu::find($id);

    $detalles = AlpDetallesMenu::select('alp_menu_detalles.*')
        ->where('alp_menu_detalles.id_menu',$id) 
        ->where('alp_menu_detalles.parent','0')->get(); 

        return view('admin.menus.detalle', compact('menu', 'detalles'));

    }



    public function storeson(Request $request, $id_menu)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);
       

        $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'parent' =>'0', 
            'order' =>0, 
            'id_menu' =>$id_menu
        );
         
        $detalle=AlpDetallesMenu::create($data);

        if ($detalle->id) {

            return redirect('admin/menus/'.$id_menu.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$id_menu.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }



    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function editson($id)
    {
       
       $detalle = AlpDetallesMenu::find($id);

        return view('admin.menus.editson', compact('detalle'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function updson(Request $request, $id)
    {
       
       

                $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'parent' =>$request->parent
                );

        $detalle = AlpDetallesMenu::find($id);


       
    
        $detalle->update($data);

        if ($detalle->id) {

            return redirect('admin/menus/'.$detalle->id_menu.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$detalle->id_menu.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

     public function submenu($id)
    {
       
        $detalle = AlpDetallesMenu::find($id);

        $detalles = AlpDetallesMenu::select('alp_menu_detalles.*')
        ->where('alp_menu_detalles.parent',$id)->get(); 

        return view('admin.menus.submenu', compact('detalle', 'detalles'));

    }

    public function storesub(Request $request, $id_detalle)
    {
        
         $user_id = Sentinel::getUser()->id;

        $detalle = AlpDetallesMenu::find($id_detalle);


        //$input = $request->all();

        //var_dump($input);
       

        $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'parent' =>$request->parent, 
            'order' =>0, 
            'id_menu' =>$detalle->id_menu
        );
         
        $detalle=AlpDetallesMenu::create($data);

        if ($detalle->id) {

            return redirect('admin/menus/'.$id_detalle.'/submenu')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$id_detalle.'/submenu')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

}
