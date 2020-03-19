<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Imports\AlpinistasImport;
use App\Imports\RetiroImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\AlpAlpinistas;
use App\User;
use App\Roles;
use App\RoleUser;
use DB;

class AlpAlpinistasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alpinistas =  AlpAlpinistas::select('alp_cod_alpinistas.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS nombre_alpinista'),'users.email as email','roles.name as name_role')
        ->leftJoin('users', 'alp_cod_alpinistas.id_usuario_creado', '=', 'users.id')
        ->leftJoin('role_users', 'alp_cod_alpinistas.id_usuario_creado', '=', 'role_users.user_id')
        ->leftJoin('roles', 'role_users.role_id', '=', 'roles.id')
        ->get();

        return view('admin.alpinistas.index', compact('alpinistas'));
    }



 public function data()
    {
       
         $alpinistas =  AlpAlpinistas::select('alp_cod_alpinistas.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS nombre_alpinista'),'users.email as email','roles.name as name_role')
        ->leftJoin('users', 'alp_cod_alpinistas.id_usuario_creado', '=', 'users.id')
        ->leftJoin('role_users', 'alp_cod_alpinistas.id_usuario_creado', '=', 'role_users.user_id')
        ->leftJoin('roles', 'role_users.role_id', '=', 'roles.id')
        ->get();

        $data = array();

        foreach($alpinistas as $row){


            if (!$row->nombre_alpinista) {
              $nombre="No Creado";
            }else{

              $nombre=$row->nombre_alpinista;

            }

            if (!$row->nombre_alpinista) {
              $email="No Creado";
            }else{
                
              $email=$row->email;
                
            }


            if ($row->estatus_alpinista == 1) {
                $estatus="<td><span class='label label-sm label-info'>Cargado</span></td>";
            }elseif($row->estatus_alpinista == 2){
                $estatus="<td><span class='label label-sm label-success'>Usuario Creado</span></td>";
            }elseif ($row->estatus_alpinista == 3) {
                $estatus="<td><span class='label label-sm label-danger'>Retirado</span></td>";
            }


        


               $data[]= array(
                 $row->id, 
                 $nombre, 
                 $email, 
                 $row->documento_alpi, 
                 $row->codigo_alpi, 
                 $row->documento_alpi, 
                 $row->cod_oracle_cliente, 
                 $estatus, 
                 $row->created_at->diffForHumans()
              );

          }

          return json_encode( array('data' => $data ));
          
      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.alpinistas.cargar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('admin.alpinistas.retirar');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import(Request $request) 
    {

        $archivo = $request->file('file_alpinistas');

        Excel::import(new AlpinistasImport, $archivo);
        
        return redirect('admin/alpinistas')->with('success', 'Alpinistas Cargados Exitosamente');
        
    }

    public function retiro(Request $request) 
    {
        $archivo = $request->file('file_alpinistas');
        Excel::import(new RetiroImport, $archivo);
        
        return redirect('admin/alpinistas')->with('success', 'Alpinistas Retirados Exitosamente');
    }

    
}
