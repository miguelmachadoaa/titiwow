<?php

namespace App\Http\Requests;
use App\Rules\Captcha;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest {

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


		switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                     'nombre_empresa' => 'required|min:3',
		            'descripcion_empresa' => 'required|min:3',
					'descuento_empresa' => 'required',
					'convenio' => 'required|unique:alp_empresas,convenio',
					'dominio' => 'required'                  
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                   'nombre_empresa' => 'required|min:3',
		            'descripcion_empresa' => 'required|min:3',
					'descuento_empresa' => 'required',
					'convenio' => 'required|unique:alp_empresas,convenio,'. $this->id_empresa,
					'dominio' => 'required'
                ];
            }
            default:
                break;
        }

		
	}

}
