<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|between:3,32',
                    'password_confirm' => 'required|same:password',
                    'doc_cliente' => 'unique:alp_clientes,doc_cliente',
                    'id_type_doc' => 'required',
                    'telefono_cliente' => 'required|min:5',
                    'habeas_cliente' => 'required',
                    'id_estructura_address' => 'required',
                    'principal_address' => 'required',
                    'secundaria_address' => 'required',
                    'edificio_address' => 'required',
                    'detalle_address' => 'required|min:2',
                    'g-recaptcha-response' => 'required|captcha'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'email' => 'required|unique:users,email,' . $this->user->id,
                    'password_confirm' => 'sometimes|same:password',
                    'doc_cliente' => 'unique:alp_clientes,doc_cliente',
                    'id_type_doc' => 'required',
                    'telefono_cliente' => 'required|min:5',
                    'habeas_cliente' => 'required',
                    'id_estructura_address' => 'required',
                    'principal_address' => 'required',
                    'secundaria_address' => 'required',
                    'edificio_address' => 'required',
                    'detalle_address' => 'required|min:2',
                    'g-recaptcha-response' => 'required|captcha'
                ];
            }
            default:
                break;
        }

    }


}

