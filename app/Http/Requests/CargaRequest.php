<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargaRequest extends FormRequest {

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
			'file_update' => 'required',
			'rol' => 'required',
			'cities' => 'required',
		];
	}


        public function messages()
    {
        return [
                    'file_update.required' => 'El Archivo es requerido',
                    'rol.required' => 'El Rol es requerido',
                    'cities.required' => 'La ciudad es requerida',
                    
                ];
    }


}
