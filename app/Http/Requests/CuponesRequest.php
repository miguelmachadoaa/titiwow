<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuponesRequest extends FormRequest {

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
            'codigo_cupon' => 'required',
            'valor_cupon' => 'required',
            'tipo_reduccion' => 'required',
            'limite_uso' => 'required'
		];
	}

}
