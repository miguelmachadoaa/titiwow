<?php

namespace App\Http\Requests;
use App\Rules\Captcha;

use Illuminate\Foundation\Http\FormRequest;

class ComentarioRespuestaRequest extends FormRequest {

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
					'id_ticket_respuesta' => 'required',
					'titulo_ticket_respuesta' => 'required|min:3',
					'texto_ticket_respuesta' => 'required|min:3',
					 
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                	'id_ticket_respuesta' => 'required',
					'titulo_ticket_respuesta' => 'required|min:3',
					'texto_ticket_respuesta' => 'required|min:3',  
                ];
            }
            default:
                break;
        }

		
	}

}
