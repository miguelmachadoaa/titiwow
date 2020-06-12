<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpCms;
use App\Http\Requests;
use App\Http\Requests\CmsRequest;
use App\Http\Requests\CmsUpdateRequest;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;


class AlpCmsController extends JoshController
{


    private $tags;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('cms/index ');

        }else{

          activity()->log('cms/index');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['cms.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




        // Grab all the blogs
        $cmss = AlpCms::all();
        // Show the page
        return view('admin.cms.index', compact('cmss'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('cms/create ');

        }else{

          activity()->log('cms/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['cms.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return view('admin.cms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CmsRequest $request)
    {


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('cms/store ');

        }else{

          activity()->withProperties($request->all())->log('cms/store');


        }

        $robots='';

        $input=$request->all();

        //dd($input);
        //
        $i=1;

        foreach ($input as $key => $value) {

            if (substr($key,0,6)=='robots') {

                if ($i==1) {

                   $robots=$value;

                   $i=0;

                }else{

                    $robots=$robots.' ,'.$value;
                }

            }
            # code...
        }


        $cms = new AlpCms($request->all());



        $message=$request->get('texto_pagina');
        libxml_use_internal_errors(true);
        $dom = new DomDocument();
        $dom->loadHtml($message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $cms->texto_pagina = $dom->saveHTML();

        $cms->id_user = Sentinel::getUser()->id;
        $cms->save();


         //  dd($cms);

        $data = array('robots' => $robots);

        $cms->update($data);    

        if ($cms->id) {
            return redirect('admin/cms')->with('exito', 'Página Guardada Exitosamente');
        } else {
            return redirect('admin/cms')->withInput()->with('error', 'Error al Guardar la Página');
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show(Blog $blog)
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$blog])->log('cms/update ');

        }else{

          activity()->withProperties(['id'=>$blog])->log('cms/update');


        }


if (!Sentinel::getUser()->hasAnyAccess(['cms.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




        /*$comments = Blog::find($blog->id)->comments;

        return view('admin.blog.show', compact('blog', 'comments', 'tags'));*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function edit($id)
    {


             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('cms/update ');

        }else{

          activity()->withProperties(['id'=>$id])->log('cms/update');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['cms.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $cms = AlpCms::find($id);

        $robots=explode(' ,', $cms->robots);

        return view('admin.cms.edit', compact('cms', 'robots'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(CmsUpdateRequest $request, $id)
    {

             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('cms/update ');

        }else{

          activity()->withProperties($request->all())->log('cms/update');


        }


        $input=$request->all();

        $message=$request->get('texto_pagina');
        libxml_use_internal_errors(true);
        $dom = new DomDocument();
        $dom->loadHtml($message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $data = array(
                'titulo_pagina' => $request->titulo_pagina, 
                'texto_pagina' => $dom->saveHTML(),                 
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'slug' => $request->slug
            );

        $cms = AlpCms::find($id);
    
        $cms->update($data);

        $i=1;


         foreach ($input as $key => $value) {

            if (substr($key,0,6)=='robots') {

                if ($i==1) {

                   $robots=$value;

                   $i=0;

                }else{

                    $robots=$robots.' ,'.$value;
                }

            }
            # code...
        }


        $data = array('robots' => $robots );

        $cms->update($data);

        if ($cms->id) {
            return redirect('admin/cms')->with('success', 'Página Editada Correctamente');
        } else {
            return redirect('admin/cms')->withInput()->with('error', 'Error al editar la página');
        }
    }

    /**
     * Remove blog.
     *
     * @param Blog $blog
     * @return Response
     */
    public function getModalDelete(cms $cms)
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$cms])->log('cms/getModalDelete ');

        }else{

          activity()->withProperties(['id'=>$cms])->log('cms/getModalDelete');


        }



        $model = 'AlpCms';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('admin.cms.delete', ['id' => $cms->id]);
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('blog/message.error.destroy', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function destroy(Cms $cms)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$cms])->log('cms/destroy ');

        }else{

          activity()->withProperties(['id'=>$cms])->log('cms/destroy');


        }

        
        if ($cms->delete()) {
            return redirect('admin/cms')->with('success', 'Página Eliminada con exito');
        } else {
            return Redirect::route('admin/cms')->withInput()->with('error', 'Error al eliminar la página');
        }
    }

}
