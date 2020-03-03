<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\TipoDocumentoRequest;
use App\Models\AlpTDocumento;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpTipoDocumentosController extends JoshController
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
                        ->log('AlpTipoDocumentosController/index ');

        }else{

          activity()
          ->log('AlpTipoDocumentosController/index');


        }
          $documentos = AlpTDocumento::all();
       


        // Show the page
        return view('admin.documentos.index', compact('documentos'));
    }

 public function data()
    {
       
          $documentos = AlpTDocumento::all();

        $data = array();

        foreach($documentos as $row){


        $actions = " <a href='".secure_url('admin/documentos/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar Documentos'></i>
                                            </a>


                                            
                                            <a href='".secure_url('admin/documentos/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";


               $data[]= array(
                 $row->id, 
                 $row->nombre_tipo_documento, 
                 $row->abrev_tipo_documento, 
                 date("d/m/Y H:i:s", strtotime($row->created_at))
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
                        ->log('AlpTipoDocumentosController/create ');

        }else{

          activity()
          ->log('AlpTipoDocumentosController/create');


        }



        return view ('admin.documentos.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(TipoDocumentoRequest $request)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpTipoDocumentosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpTipoDocumentosController/store');


        }


        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_tipo_documento' => $request->nombre_tipo_documento, 
            'abrev_tipo_documento' => $request->abrev_tipo_documento, 
            'id_user' =>$user_id
        );
         
        $documentos=AlpTDocumento::create($data);

        if ($documentos->id) {

            return redirect('admin/documentos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/documentos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpTipoDocumentosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpTipoDocumentosController/edit');


        }


       
       $documentos = AlpTDocumento::find($id);

        return view('admin.documentos.edit', compact('documentos'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(TipoDocumentoRequest $request, $id)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpTipoDocumentosController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpTipoDocumentosController/update');


        }


       $data = array(
            'nombre_tipo_documento' => $request->nombre_tipo_documento, 
            'abrev_tipo_documento' => $request->abrev_tipo_documento
        );
         
       $documentos = AlpTDocumento::find($id);
    
        $documentos->update($data);

        if ($documentos->id) {

            return redirect('admin/documentos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/documentos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'documentos';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $documentos = AlpTDocumento::find($id);

            $confirm_route = route('admin.documentos.delete', ['id' => $documentos->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpTipoDocumentosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpTipoDocumentosController/edit');


        }

        
        try {
            // Get group information
           
            $documentos = AlpTDocumento::find($id);


            // Delete the group
            $documentos->delete();

            // Redirect to the group management page
            return Redirect::route('admin.documentos.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.documentos.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
