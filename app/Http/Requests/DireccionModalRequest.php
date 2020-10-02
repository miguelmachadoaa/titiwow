<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DireccionModalRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}


	  public function messages()
    {
        return [
                    'titulo.required' => 'El Titulo es requerido',
                    'principal_address.required' => 'La Principal es requerida',
                    'secundaria_address.required' => 'La Secundaria es requerida',
                    'detalle_address.required' => 'El Detalle es requerido',
                    //'barrio_address.required' => 'El Barrio es requerido',
                    'city_id.required' => 'La Ciudad es requerida',
                    'id_estructura_address.required' => 'El tipo de Estructura es requerido'
                ];
    }


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'principal_address_dir' => 'required',
            'secundaria_address_dir' => 'required',
            'edificio_address_dir' => 'required',
            'detalle_address_dir' => 'required',
            //'barrio_address' => 'required',
            'city_id_dir' => 'required',
            'id_estructura_address_dir' => 'required'
		];
	}

}
