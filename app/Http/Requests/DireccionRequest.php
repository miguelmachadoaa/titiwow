<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DireccionRequest extends FormRequest {

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
            'id_client	' => 'required',
            'city_id	' => 'required',
            'nickname_address' => 'required|min:3',
            'calle_address' => 'required|min:3',
            'codigo_postal_address' => 'required|min:3',
            'telefono_address' => 'required|min:3',
		];
	}

}
