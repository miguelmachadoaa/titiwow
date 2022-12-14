<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaenvioRequest extends FormRequest {

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
            'sku' => 'required|min:3',
            'nombre_forma_envios' => 'required|min:3',
            'descripcion_forma_envios' => 'required|min:3',
		];
	}

}
