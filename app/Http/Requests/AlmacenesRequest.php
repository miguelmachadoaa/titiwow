<?php

namespace App\Http\Requests;
use App\Rules\Captcha;

use Illuminate\Foundation\Http\FormRequest;

class AlmacenesRequest extends FormRequest {

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

		switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                     'nombre_almacen' => 'required|min:3',
                     'alias_almacen' => 'required|min:3',
		            'descripcion_almacen' => 'required|min:3',
					'defecto' => 'required',
					'hora' => 'required',
					'correos' => 'required',
					'minimo_compra' => 'required',
					'tipo_almacen' => 'required',
					'id_estructura_address' => 'required',
					'principal_address' => 'required',
					'secundaria_address' => 'required',
					'edificio_address' => 'required',
					'detalle_address' => 'required',
					'barrio_address' => 'required',
					'city_id' => 'required'               
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                   'nombre_almacen' => 'required|min:3',
		            'descripcion_almacen' => 'required|min:3',
					'defecto' => 'required',
					'hora' => 'required',
					'correos' => 'required',
					'minimo_compra' => 'required',
					'tipo_almacen' => 'required',
					'id_estructura_address' => 'required',
					'principal_address' => 'required',
					'secundaria_address' => 'required',
					'edificio_address' => 'required',
					'detalle_address' => 'required',
					'barrio_address' => 'required',
					'city_id' => 'required'  
                ];
            }
            default:
                break;
        }

		
	}

}
