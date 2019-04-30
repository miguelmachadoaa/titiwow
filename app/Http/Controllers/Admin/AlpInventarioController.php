<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\FormapagoRequest;
use App\Models\AlpProductos;
use App\Models\AlpInventario;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpInventarioController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $productos = AlpProductos::all();

        $inventario=$this->inventario();
       


        // Show the page
        return view('admin.inventario.index', compact('productos', 'inventario'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.inventario.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(FormapagoRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_forma_pago' => $request->nombre_forma_pago, 
            'descripcion_forma_pago' => $request->descripcion_forma_pago, 
            'id_user' =>$user_id
        );
         
        $forma=AlpProductos::create($data);

        if ($forma->id) {

            return redirect('admin/formaspago')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formaspago')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $producto = AlpProductos::find($id);

        $inventario=$this->inventario();



        return view('admin.inventario.edit', compact('producto', 'inventario'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {


         $user_id = Sentinel::getUser()->id;


       $data = array(
            'id_producto' => $id, 
            'cantidad' => $request->cantidad, 
            'operacion' => $request->operacion, 
            'notas' => $request->notas, 
            'id_user' => $user_id
        );
         
       $inventario = AlpInventario::create($data);
    
      

        if ($inventario->id) {

            return redirect('admin/inventario')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/inventario')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'formaspago';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpProductos::find($id);

            $confirm_route = route('admin.inventario.delete', ['id' => $forma->id]);

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
           
            $forma = AlpProductos::find($id);


            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.inventario.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.inventario.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    private function inventario()
    {
       

      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;
      
    }

    

}
