<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CmsRequest extends FormRequest {

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
            'titulo_pagina' => 'required|min:3',
            'texto_pagina' => 'required|min:3',
			'seo_titulo' => 'required',
			'seo_descripcion' => 'required',
			'slug' => 'required|unique:alp_cms',

		];
	}

}
