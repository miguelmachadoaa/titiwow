<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstatusPagosRequest extends FormRequest {

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
            'estatus_pago_nombre' => 'required|min:3',
            'estatus_pago_descripcion' => 'required|min:5'
		];
	}

}
