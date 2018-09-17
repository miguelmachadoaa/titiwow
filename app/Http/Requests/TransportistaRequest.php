<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportistaRequest extends FormRequest {

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
            'nombre_transportista' => 'required|min:3',
            'descripcion_transportista' => 'required|min:5'
		];
	}

}
