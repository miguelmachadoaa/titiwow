<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\State;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpEmpresas;
use App\Models\AlpAmigos;
use App\Http\Requests\EmpresaRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Imports\InvitacionesImport;
use Maatwebsite\Excel\Facades\Excel;

use Redirect;
use Sentinel;
use View;


class AlpEmpresasController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $empresas = AlpEmpresas::all();
       


        // Show the page
        return view('admin.empresas.index', compact('empresas'));
    }


  

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.empresas.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(EmpresaRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);


          $imagen='default.png';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path('uploads/empresas/' . $picture);    
            Image::make($file)->resize(400, 400)->save($destinationPath);            
            $imagen = $picture;

        }



        $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa, 
            'descuento_empresa' => $request->descuento_empresa, 
            'dominio' => $request->dominio, 
            'imagen' => $imagen, 
            'id_user' =>$user_id
        );
         
        $empresas=AlpEmpresas::create($data);

        if ($empresas->id) {

            return redirect('admin/empresas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/empresas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $empresas = AlpEmpresas::find($id);

        return view('admin.empresas.edit', compact('empresas'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(EmpresaRequest $request, $id)
    {



        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path('uploads/empresas/' . $picture);    
            Image::make($file)->resize(400, 400)->save($destinationPath);            
            $imagen = $picture;

             $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa,
            'descuento_empresa' => $request->descuento_empresa,
            'dominio' => $request->dominio, 
            
            'imagen' => $imagen
        );



        }else{

                $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa,
            'dominio' => $request->dominio, 
            'descuento_empresa' => $request->descuento_empresa
                );

        }

         
       $empresas = AlpEmpresas::find($id);
    
        $empresas->update($data);

        if ($empresas->id) {

            return redirect('admin/empresas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/empresas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'empresas';
        $confirm_route = $error = null;
        try {
            // Get group inempresastion
            
            $empresas = AlpEmpresas::find($id);

            $confirm_route = route('admin.empresas.delete', ['id' => $empresas->id]);

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
            // Get group inempresastion
           
            $empresas = AlpEmpresas::find($id);

            // Delete the group
            $empresas->delete();

            // Redirect to the group management page
            return Redirect::route('admin.empresas.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.empresas.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

     public function invitaciones($id)
    {

        $empresa = AlpEmpresas::find($id);

        $invitaciones = AlpAmigos::where('id_cliente', 'E'.$id)->get();
        // Show the page
        return view('admin.empresas.invitaciones', compact('empresa','invitaciones'));
    }



    public function storeamigo(Request $request)
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $token=substr(md5(time()), 0, 10);

            $data = array(
                'id_cliente' => 'E'.$request->id_empresa, 
                'nombre_amigo' => $request->nombre, 
                'apellido_amigo' => $request->apellido, 
                'email_amigo' => $request->email, 
                'token' => $token, 
                'id_user' => $user_id
            );

            AlpAmigos::create($data);

            $invitaciones=AlpAmigos::where('id_cliente', 'E'.$request->id_empresa)->get();

            $empresa = AlpEmpresas::find($request->id_empresa);

            Mail::to($request->email)->send(new \App\Mail\NotificacionAfiliado($request->nombre, $request->apellido, $token, $empresa->nombre_empresa));


            $view= View::make('admin.empresas.listamigo', compact('invitaciones', 'empresa'));

            $data=$view->render();

            return $data;

            }

       
    }

    public function delamigo(Request $request)
    {

            $user_id = Sentinel::getUser()->id;

            $amigo=AlpAmigos::find($request->id);

            $empresa=$amigo->id_cliente;

            $amigo->delete();

            $invitaciones=AlpAmigos::where('id_cliente', $empresa)->get();

            $empresa = AlpEmpresas::find($request->id_empresa);

            $view= View::make('admin.empresas.listamigo', compact('invitaciones', 'empresa'));

            $data=$view->render();

            return $data;
       
    }


     public function afiliado($id)
    {

        $amigo=AlpAmigos::where('token', $id)->first();

        $states=State::where('config_states.country_id', '47')->get();

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

 
        if (!isset($amigo->id)) {

                $mensaje="Su enlace de registro ha vencido, solicite uno nuevo o registrese como cliente";

                return view('frontend.clientes.aviso',  compact('mensaje'));

        }
                
        return view('frontend.empresas.registro',  compact('id', 'amigo', 'states','t_documento','estructura'));
        
    }

    public function invitacionesmasiv()
    {

        $empresa = AlpEmpresas::all();

        /*$invitaciones = AlpAmigos::where('id_cliente', 'E'.$id)->get();*/
        // Show the page
        return view('admin.empresas.cargar', compact('empresa'));
    }

    public function import(Request $request) 
    {
        $archivo = $request->file('file_invitaciones');
        Excel::import(new InvitacionesImport, $archivo);
        
        return redirect('admin/empresas/invitacionesmasiv')->with('success', 'Invitaciones Cargadas Exitosamente');
    }

}
