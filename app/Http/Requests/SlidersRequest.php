<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlidersRequest extends FormRequest {

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
            'nombre_slider' => 'required|min:3',
			'descripcion_slider' => 'required|min:5',
			'image' => 'mimes:jpg,png,jpeg,pdf|max:1024',
			'image_mobile' => 'mimes:jpg,png,jpeg,pdf|max:1024',
		];
	}

}
