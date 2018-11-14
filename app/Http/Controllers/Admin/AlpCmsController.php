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
        return view('admin.cms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CmsRequest $request)
    {
        $cms = new AlpCms($request->all());
        $message=$request->get('texto_pagina');
        libxml_use_internal_errors(true);
        $dom = new DomDocument();
        $dom->loadHtml($message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $cms->texto_pagina = $dom->saveHTML();

        $cms->id_user = Sentinel::getUser()->id;
        $cms->save();

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
        $cms = AlpCms::find($id);
        return view('admin.cms.edit', compact('cms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(CmsUpdateRequest $request, $id)
    {
        
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
        if ($cms->delete()) {
            return redirect('admin/cms')->with('success', 'Página Eliminada con exito');
        } else {
            return Redirect::route('admin/cms')->withInput()->with('error', 'Error al eliminar la página');
        }
    }

}
