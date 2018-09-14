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


class AlpMeuController extends JoshController
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
            'nombre_menu' => $request->nombre_menu
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

      

    $detalles = AlpMenu::select('alp_menu_detalles.*')
        ->where('alp_menu_detalles.id_menu',$id)->get(); 



        return view('admin.menus.detalle', compact('menu', 'detalles'));

    }

    public function storeson(Request $request, $padre)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);


        
       

        $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'parent' =>$padre, 
            'order' =>0, 
            'id_menu' =>$padre
        );
         
        $detalle=AlpDetallesMenu::create($data);

        if ($detalle->id) {

            return redirect('admin/menus/'.$padre.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$padre.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $categoria = AlpMenu::find($id);

        return view('admin.menus.editson', compact('categoria'));
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
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'id_categoria_parent' =>$request->id_categoria_parent
                );

        


     /*  $categoria = AlpMenu::find($id);
    
        $categoria->update($data);

        if ($categoria->id) {

            return redirect('admin/menus/'.$request->id_categoria_parent.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$request->id_categoria_parent.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  */

    }

}
