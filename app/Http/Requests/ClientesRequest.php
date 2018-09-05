<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientesRequest extends FormRequest
{

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
                    'id_type_doc' => 'required',
                    'doc_cliente' => 'required|min:3',
                    'telefono_cliente' => 'required|between:3,32',
                    'habeas_cliente' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'id_type_doc' => 'required',
                    'doc_cliente' => 'required|min:3',
                    'telefono_cliente' => 'required|between:3,32',
                    'habeas_cliente' => 'required'
                ];
            }
            default:
                break;
        }

    }


}

