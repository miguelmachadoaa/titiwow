<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\MarcaRequest;
use App\Models\AlpMarcas;
use App\Http\Requests;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Redirect;
use Sentinel;
use View;


class AlpMarcasController extends JoshController
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
                        ->log('AlpMarcasController/index ');

        }else{

          activity()
          ->log('AlpMarcasController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      

        $marcas = AlpMarcas::all();
       


        // Show the page
        return view('admin.marcas.index', compact('marcas'));
    }





     public function data()
    {
       
        $marcas = AlpMarcas::all();
       

            $data = array();


          foreach($marcas as $row){


           


                 $actions = " <a href='".secure_url('admin/marcas/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>
 <a href='".secure_url('admin/marcas/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";

             


                                          


               $data[]= array(
                 $row->id, 
                 $row->nombre_marca, 
                 $row->descripcion_marca, 
                 $row->order, 
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
        // Show the page

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpMarcasController/create ');

        }else{

          activity()
          ->log('AlpMarcasController/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        return view ('admin.marcas.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(MarcaRequest $request)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMarcasController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMarcasController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);



         $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('uploads/marcas/' . $picture);    
            Image::make($file)->resize(600, 600)->save($destinationPath);            
            $imagen = $picture;

        }

        $data = array(
            'nombre_marca' => $request->nombre_marca, 
            'descripcion_marca' => $request->descripcion_marca, 
            'imagen_marca' => $imagen, 
            'slug' => $request->slug, 
            'seo_titulo' => $request->seo_titulo,
            'seo_descripcion' => $request->seo_descripcion,
            'order' => $request->order, 
            'destacado' => $request->destacado, 
            'id_user' =>$user_id
        );
         
        $forma=AlpMarcas::create($data);

        if ($forma->id) {

            return redirect('admin/marcas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/marcas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpMarcasController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMarcasController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       
       $marca = AlpMarcas::find($id);

        return view('admin.marcas.edit', compact('marca'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(MarcaRequest $request, $id)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpMarcasController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpMarcasController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       $imagen='0';
       $picture = "";

       if ($request->hasFile('image')) {
            
        $file = $request->file('image');
        $extension = $file->extension()?: 'png';
        $picture = str_random(10) . '.' . $extension;    
        $destinationPath = public_path('uploads/marcas/' . $picture);    
        Image::make($file)->resize(600, 600)->save($destinationPath);            
        $imagen = $picture;

        $data = array(
            'nombre_marca' => $request->nombre_marca, 
            'descripcion_marca' => $request->descripcion_marca,
            'destacado' => $request->destacado,
            'order' => $request->order,
            'slug' => $request->slug,
            'seo_titulo' => $request->seo_titulo,
            'seo_descripcion' => $request->seo_descripcion,
            'imagen_marca' =>$imagen,
        );

    }else{

        $data = array(
            'nombre_marca' => $request->nombre_marca, 
            'descripcion_marca' => $request->descripcion_marca,
            'destacado' => $request->destacado,
            'order' => $request->order,
            'slug' => $request->slug,
            'seo_titulo' => $request->seo_titulo,
            'seo_descripcion' => $request->seo_descripcion
        );


    }

         
       $marca = AlpMarcas::find($id);
    
        $marca->update($data);

        if ($marca->id) {

            return redirect('admin/marcas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/marcas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'marcas';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $marca = AlpMarcas::find($id);

            $confirm_route = route('admin.marcas.delete', ['id' => $marca->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpMarcasController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpMarcasController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        
        try {
            // Get group information
           
            $marca = AlpMarcas::find($id);


            // Delete the group
            $marca->delete();

            // Redirect to the group management page
            return Redirect::route('admin.marcas.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.marcas.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
