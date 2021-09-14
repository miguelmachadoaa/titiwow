<?php

namespace app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbonoRequest extends FormRequest {

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
           'codigo_abono' => 'min:20|unique:alp_abonos,codigo_abono',
           'valor_abono' => 'required',
           'fecha_final' => 'required',
           'id_almacen' => 'required'
		];
	}

}
