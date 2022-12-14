<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductosRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'nombre_producto' => 'required|min:3',
            'referencia_producto' => 'unique:alp_productos,referencia_producto|required|min:2',
            'referencia_producto_sap' => 'unique:alp_productos,referencia_producto_sap|required|min:2',
            'descripcion_corta' => 'required|min:5',
            'descripcion_larga' => 'required|min:5',
            'seo_titulo' => 'required|min:5',
            'seo_descripcion' => 'required|min:5',
			'slug' => 'required|unique:alp_productos',
            'id_categoria_default' => 'required',
            'id_marca' => 'required',
            'id_impuesto' => 'required',
            'medida' => 'required',
            'inventario_inicial' => 'required',
            'mostrar_descuento' => 'required',
			'precio_base' => 'required',
			'cantidad' => 'required|numeric',
			'order' => 'required|numeric',
			'unidad' => 'required',
			'imagen_producto' => 'image',
		];
	}

	public function messages()
    {
        return [
                    'order.required' => 'El Campo Orden es Requerido'
                    
                ];
    }

}
