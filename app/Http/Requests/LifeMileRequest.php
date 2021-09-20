<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LifeMileRequest extends FormRequest {

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
           'nombre_lifemile' => 'required',
           'cantidad_millas' => 'required',
           'minimo_compra' => 'required',
           'fecha_inicio' => 'required',
           'fecha_final' => 'required',
           'id_almacen' => 'required'
		];
	}

}
