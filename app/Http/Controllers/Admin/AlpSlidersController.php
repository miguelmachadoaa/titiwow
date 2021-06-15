<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\SlidersRequest;
use App\Models\AlpSliders;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenSlider;
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


        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        // Grab all the blogs

        $sliders = AlpSliders::get();

        // Show the page
        return view('admin.sliders.index', compact('sliders'));
    }


     public function data()
    {
       
        $sliders = AlpSliders::get();

        $data = array();

        foreach($sliders as $row){

            $imagen="<img src='../uploads/sliders/".$row->imagen_slider."' height='60px'>";

            $actions = "   <a href='".secure_url('admin/sliders/'.$row->id.'/edit')."'>
                            <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Estado de Envio'></i>
                        </a>


                        <!-- let's not delete 'Admin' group by accident -->
                        
                        <a href='".secure_url('admin/sliders/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                        <i class='livicon' data-name='remove-alt' data-size='18'
                            data-loop='true' data-c='#f56954' data-hc='#f56954'
                            title='Eliminar'></i>
                         </a>





                            

                                             "


                                             ;




               $data[]= array(
                 $row->id, 
                 $row->nombre_slider, 
                 $row->descripcion_slider, 
                 $row->link_slider, 
                 $imagen, 
                 $row->order, 
                date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        
        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $almacenes=AlpAlmacenes::get();


        return view('admin.sliders.create', compact('almacenes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SlidersRequest $request)
    {
        
        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        if (Sentinel::check()) {

            $user = Sentinel::getUser();
  
             activity($user->full_name)
                          ->performedOn($user)
                          ->causedBy($user)
                          ->withProperties($request->all())
                          ->log('AlpSlidersController/store');
  
          }else{
  
            activity()->withProperties($request->all())->log('AlpSlidersController/store');
  
  
          }

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

       // dd($input);

        $imagen='default.png';
        $imagen_slider='default.png';

         $picture = "";
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');   
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path() . '/uploads/sliders/';
            $file->move($destinationPath, $picture);
            $imagen = $picture;
        }


        if ($request->hasFile('image_mobile')) {

            $file = $request->file('image_mobile');
            $extension = 'jpg';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('/uploads/sliders/' . $picture);
            Image::make($file)->resize(520, 402)->save($destinationPath);            
            $image_mobile = $picture;

        }


               

        $data = array(
            'nombre_slider' => $request->nombre_slider, 
            'descripcion_slider' => $request->descripcion_slider,
            'imagen_slider' =>$imagen, 
            'imagen_slider_mobile' =>$image_mobile,
            'order' => $request->order,
            'link_slider' => $request->link_slider,
            'id_user' =>$user_id
        );
         
        $slider=AlpSliders::create($data);




        foreach ($input as $key => $value) {

            if (substr($key,0,7)=='almacen') {

                $data_sa = array(
                    'id_almacen' =>$value,
                    'id_slider' =>$slider->id,
                    'id_user' =>$user_id
                );

                AlpAlmacenSlider::create($data_sa);

            }
            # code...
        }

        /*se guarda inventario*/

        if ($slider->id) {

            return redirect('admin/sliders');

        } else {
            return Redirect::route('admin/sliders')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        
        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

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
        
        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

      $slider=AlpSliders::where('id', $id)->first();


      $almacenes=AlpAlmacenes::get();

      $almacenslider=AlpAlmacenSlider::where('id_slider', '=', $id)->get();

      $as = array();

      foreach ($almacenslider as $almacens) {

            $as[$almacens->id_almacen]=$almacens->id_almacen;
          # code...
      }

     // dd($as);
        

        return view('admin.sliders.edit', compact('slider', 'almacenes', 'as'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(SlidersRequest $request, $id)
    {
        
        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        if (Sentinel::check()) {

        $user = Sentinel::getUser();

            activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('AlpSlidersController/update');

        }else{

        activity()->withProperties($request->all())->log('AlpSlidersController/update');


        }

        $slider = AlpSliders::find($id);


        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $imagen='0';

        $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path() . '/uploads/sliders/';
            $file->move($destinationPath, $picture);
            $imagen = $picture;

            $data = array(
                'nombre_slider' => $request->nombre_slider, 
                'descripcion_slider' => $request->descripcion_slider,
                'imagen_slider' =>$imagen,
                'order' => $request->order,
                'link_slider' => $request->link_slider
                );
        }else{
            $data = array(
                'nombre_slider' => $request->nombre_slider, 
                'descripcion_slider' => $request->descripcion_slider,
                'order' => $request->order,
                'link_slider' => $request->link_slider
                );

        }

        // dd($data);
    
        $slider->update($data);

        if ($request->hasFile('image_mobile')) {

            $file = $request->file('image_mobile');
            $extension = 'jpg';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('/uploads/sliders/' . $picture);
            Image::make($file)->resize(520, 402)->save($destinationPath);         
            $image_mobile = $picture;


            $data = array(
                'imagen_slider_mobile' =>$image_mobile
                );

             $slider->update($data);


        }



        AlpAlmacenSlider::where('id_slider', '=', $id)->delete();

         foreach ($input as $key => $value) {

            if (substr($key,0,7)=='almacen') {

                $data_sa = array(
                    'id_almacen' =>$value,
                    'id_slider' =>$id,
                    'id_user' =>$user_id
                );

                AlpAlmacenSlider::create($data_sa);

            }
            # code...
        }

        if ($slider->id) {

            return redirect('admin/sliders');

        } else {
            return Redirect::route('admin/sliders')->withInput()->with('error', trans('Ha ocurrido un error al editar el registro'));
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
        
        if (!Sentinel::getUser()->hasAnyAccess(['sliders.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      $id_slider=$slider;

      $slider=AlpSliders::where('id', $id_slider)->first();

        if ($slider->delete()) {

            return redirect('admin/sliders')->with('success', trans('Registro Eliminado Satisfactoriamente'));


        } else {


            return Redirect::route('admin/sliders')->withInput()->with('error', trans('Error al eliminar Registro'));


        }
    }

   


  


}
