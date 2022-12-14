<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormapagoRequest extends FormRequest {

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
            'nombre_forma_pago' => 'required|min:3',
            'orden' => 'required',
            'descripcion_forma_pago' => 'required|min:5'
		];
	}

}
