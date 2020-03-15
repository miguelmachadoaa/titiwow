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
		            'descripcion_almacen' => 'required|min:3',
					'defecto' => 'required',
					'city_id' => 'required'               
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                   'nombre_almacen' => 'required|min:3',
		            'descripcion_almacen' => 'required|min:3',
					'defecto' => 'required',
					'city_id' => 'required'  
                ];
            }
            default:
                break;
        }

		
	}

}
