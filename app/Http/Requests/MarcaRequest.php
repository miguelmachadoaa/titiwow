<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcaRequest extends FormRequest {

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
            'nombre_marca' => 'required|min:3',
            'slug' => 'required|min:3',
			'descripcion_marca' => 'required|min:5',
			'image' => 'image',
		];
	}

}
