<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpMenu;
use App\Models\AlpDetalleSubmenu;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use DB;
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

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpMenuController/index ');

        }else{

          activity()
          ->log('AlpMenuController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

      

        $menus = AlpMenu::all();
       

        // Show the page
        return view('admin.menus.index', compact('menus'));
    }


      public function data()
    {
       
        
      $menus = AlpMenu::all();

        $data = array();

        foreach($menus as $row){

           
        $actions = "      <a href='".secure_url('admin/menus/'.$row->id.'/detalle')."'>
                                                <i class='livicon' data-name='plus' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Detalle'></i>
                                            </a>



                                            <a href='".secure_url('admin/menus/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>



                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href='".secure_url('admin/menus/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>

                                             <a href='".secure_url('admin/menus/'.$row->id.'/ordenar')."'>
                                                <i class='livicon' data-name='list' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Ordenar Menu'></i>
                                            </a>




";

                


               $data[]= array(
                 $row->id, 
                 $row->nombre_menu, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $actions
              );

          }

          return json_encode( array('data' => $data ));
          
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
                        ->log('AlpMenuController/create ');

        }else{

          activity()
          ->log('AlpMenuController/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


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

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMenuController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMenuController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        
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

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



       
       $menu = AlpMenu::find($id);

        return view('admin.menus.edit', compact('menu'));
    }


     public function ordenar($id)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/ordenar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/ordenar');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



       
        $menus = AlpDetalleSubmenu::where('alp_menu_detalles.id_menu',$id)
        ->where('parent', '=', 0)->orderby('order')->get();

        foreach ($menus as $m) {

            $h = AlpDetalleSubmenu::where('alp_menu_detalles.parent',$m->id)->orderby('order')->get();

            foreach ($h as $hh) {

                $a = AlpDetalleSubmenu::where('alp_menu_detalles.parent',$hh->id)->orderby('order')->get();

                foreach ($a as $aa) {

                    $b = AlpDetalleSubmenu::where('alp_menu_detalles.parent',$hh->id)->orderby('order')->get();

                    if (count($b)) {
                        $aa->hijos=$b;
                    }
                }

                if (count($a)) {
                     $hh->hijos=$a;
                }

               
            }


            if (count($h)) {
                $m->hijos=$h;
            }


             
            
            
        }

        //dd($menus);

        return view('admin.menus.orden', compact('menus'));
    }


     public function postordenar(Request $request, $id)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/ordenar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/ordenar');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        $input=$request->all();

         $datos = json_decode($input['orden']);

        $i=0;
         foreach ($datos as $d) {

            

            $i++;
             
             $data = array(
                'order' => $i ,
                'parent' => '0' 
            );



             $m=AlpDetalleSubmenu::where('id', $d->id)->first();

             $m->update($data);
            

             if (isset($d->children)) {

                $j=0;
                 
                foreach ($d->children as $b ) {

                    $j++;

                    $datab = array(
                        'order' => $j,
                        'parent' => $d->id,
                    );

                    $n=AlpDetalleSubmenu::where('id', $b->id)->first();

                    $n->update($datab);


                    if (isset($b->children)) {

                        $k=0;
                        foreach ($b->children as $c ) {

                            $k++;

                              $datac = array(
                                    'order' => $k,
                                    'parent' => $b->id,
                                );

                                $l=AlpDetalleSubmenu::where('id', $c->id)->first();

                                $l->update($datac);


                        }

                        

                    }


                }


             }
         }


        return json_encode($datos);
    }




    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
      

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMenuController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMenuController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

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

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/destroy');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



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

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/detalle ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/detalle');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




       
       $menu = AlpMenu::find($id);

        $detalles = AlpDetalleSubmenu::where('alp_menu_detalles.id_menu',$id) 
        ->where('alp_menu_detalles.parent','0')->get();

        return view('admin.menus.detalle', compact('menu', 'detalles'));

    }



    public function storeson(Request $request, $id_menu)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMenuController/storeson ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMenuController/storeson');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //dd($input);

        //var_dump($input);
       

        $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'open' => $request->open, 
            'parent' =>'0', 
            'order' =>0, 
            'id_menu' =>$id_menu
        );
         
        $detalle=AlpDetalleSubmenu::create($data);

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

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/editson ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/editson');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



       
       $detalle = AlpDetalleSubmenu::find($id);

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
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMenuController/updson ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMenuController/updson');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'order' => $request->order, 
            'open' => $request->open, 
            'parent' =>$request->parent
        );

        $detalle = AlpDetalleSubmenu::find($id);
    
        $detalle->update($data);

        if ($detalle->id) {

            return redirect('admin/menus/'.$detalle->id_menu.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$detalle->id_menu.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

     public function submenu($id)
    {

     //   dd($id);

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpMenuController/submenu ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMenuController/submenu');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

       
        $detalle = AlpDetalleSubmenu::find($id);

        

        $detalles = AlpDetalleSubmenu::where('alp_menu_detalles.parent',$id) ->get();

        return view('admin.menus.submenu', compact('detalle', 'detalles'));

    }

    public function storesub(Request $request, $id_detalle)
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMenuController/storesub ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMenuController/storesub');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['menus.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        
         $user_id = Sentinel::getUser()->id;

        $detalle = AlpDetalleSubmenu::find($id_detalle);

        $data = array(
            'name' => $request->name, 
            'slug' => $request->slug, 
            'parent' =>$request->parent, 
            'open' => $request->open, 
            'order' =>0, 
            'id_menu' =>$detalle->id_menu
        );
         
        $detalle=AlpDetalleSubmenu::create($data);

        if ($detalle->id) {

            return redirect('admin/menus/'.$id_detalle.'/submenu')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/menus/'.$id_detalle.'/submenu')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

}
