<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdenesRequest extends FormRequest {

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
            'referencia' => 'required|min:3',
            'id_cliente' => 'required',
            'id_forma_envio' => 'required',
            'id_forma_pago' => 'required',
            'monto_total' => 'required',
		];
	}

}
