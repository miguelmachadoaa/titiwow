<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\SlidersRequest;
use App\Models\AlpSliders;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;
use View;
use DB;


class AlpSlidersController extends JoshController
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

        $sliders = AlpSliders::get();

        // Show the page
        return view('admin.sliders.index', compact('sliders'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        

        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SlidersRequest $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);

        $imagen='default.png';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');    

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/sliders/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

        }
               

        $data = array(
            'nombre_slider' => $request->nombre_slider, 
            'descripcion_slider' => $request->descripcion_slider,
            'imagen_slider' =>$imagen, 
            'id_user' =>$user_id
        );
         
        $slider=AlpSliders::create($data);

        /*se guarda inventario*/

        if ($slider->id) {

            return redirect('sliders');

        } else {
            return Redirect::route('sliders')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }   

    }


    /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show($id)
    {

          $slider = AlpSliders::where('id', $id)->first();

        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function edit($id)
    {

      $slider=AlpSliders::where('id', $id)->first();
        

        return view('admin.sliders.edit', compact('slider'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(SlidersRequest $request, $id)
    {

        $slider = AlpSliders::find($id);


        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/sliders/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

             $data = array(
                'nombre_slider' => $request->nombre_slider, 
                'descripcion_slider' => $request->descripcion_slider,
                'imagen_slider' =>$imagen
                );



        }else{

                  $data = array(
              'nombre_slider' => $request->nombre_slider, 
                'descripcion_slider' => $request->descripcion_slider,
                );

        }

       // dd($data);
         
        $slider->update($data);

        if ($slider->id) {

            return redirect('admin/sliders');

        } else {
            return Redirect::route('admin/sliders')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }
      
    }

    /**
     * Remove blog.
     *
     * @param Blog $blog
     * @return Response
     */
    public function getModalDelete( $slider)
    {
        
       // dd($producto);

        $model = 'AlpSliders';
        $confirm_route = $error = null;
        try {

            $confirm_route = route('admin.sliders.delete', ['id' => $slider]);

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));

        } catch (GroupNotFoundException $e) {

            $error = trans('Error al eliminar Registro', compact('id'));

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function destroy( $slider)
    {


      $id_slider=$slider;

      $slider=AlpSliders::where('id', $id_slider)->first();

        if ($slider->delete()) {

            return redirect('admin/sliders')->with('success', trans('Registro Eliminado Satisfactoriamente'));


        } else {


            return Redirect::route('admin/sliders')->withInput()->with('error', trans('Error al eliminar Registro'));


        }
    }

   


  


}