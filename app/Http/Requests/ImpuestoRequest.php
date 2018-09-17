<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImpuestoRequest extends FormRequest {

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
            'nombre_impuesto' => 'required|min:3',
            'valor_impuesto' => 'required'
		];
	}

}
