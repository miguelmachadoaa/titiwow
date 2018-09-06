<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;

class ProductosFrontController extends Controller
{
    public function index()
    {
        $productos = AlpProductos::all();
        return \View::make('frontend.list', compact('productos'));
    }
 
    public function show($slug)
    {
        $producto = AlpProductos::where('slug','=', $slug)->firstOrFail();
        
        return \View::make('frontend.producto_single', compact('producto'));

    }
}
