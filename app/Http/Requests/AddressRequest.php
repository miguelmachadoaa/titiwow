<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest {

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
            'titulo	' => 'required',
            'city_id	' => 'required',
            'id_estructura_address	' => 'required',
            'principal_address	' => 'required',
            'secundaria_address	' => 'required',
            'edificio_address	' => 'required',
            'detalle_address	' => 'required',
            'barrio_address	' => 'required',
		];
	}





}
