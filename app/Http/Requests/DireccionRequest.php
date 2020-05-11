<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DireccionRequest extends FormRequest {

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
            'titulo' => 'required',
            'principal_address' => 'required',
            'secundaria_address' => 'required',
            'edificio_address' => 'required',
            'detalle_address' => 'required',
            //'barrio_address' => 'required',
            'city_id' => 'required',
            'id_estructura_address' => 'required'
		];
	}

}
