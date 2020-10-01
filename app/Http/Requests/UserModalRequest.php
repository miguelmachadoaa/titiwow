<?php

namespace App\Http\Requests;
use App\Rules\Captcha;

use Illuminate\Foundation\Http\FormRequest;

class UserModalRequest extends FormRequest
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


        public function messages()
    {
        return [
                    'first_name.required' => 'El Nombre es requerido',
                    'first_name.min' => 'El Nombre de contener como minimo 3 caracteres',
                    'last_name.required' => 'El Apellido es requerido',
                    'last_name.min' => 'El Apellido debe ser mayor a 3 caracteres',
                    'email.required' => 'El Email es requerido',
                    'password.required' => 'La Clave es requerido',
                    'password.between' => 'La clave debe contener entre 3 y 32 caracteres',
                    'password_confirm.required' => 'El Confirmacion de Clave es requerido',
                    'doc_cliente.required' => 'El Documento es requerido',
                    'id_type_doc.required' => 'El Tipo de Documento  es requerido',
                    'telefono_cliente.required' => 'El Telefono  es requerido',
                    'habeas_cliente.required' => 'Aceptar Terminos y condiciones  es requerido',
                    'id_estructura_address' => 'La Estructura es Requerida',
                    'principal_address' => 'La Principal es requerida',
                    'secundaria_address' => 'La Secundaria es requerida',
                    'edificio_address' => 'El Edifico es requerido',
                    'g-recaptcha-response' => new Captcha(),
                ];
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
                  //  'email' => 'required|email|unique:users,email',
                    'doc_cliente' => 'unique:alp_clientes,doc_cliente',
                    'id_type_doc' => 'required',
                    'telefono_cliente' => 'required|min:5',
                    'id_estructura_address' => 'required',
                    'principal_address' => 'required',
                    'secundaria_address' => 'required',
                    'edificio_address' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                   // 'email' => 'required|unique:users,email,' . $this->user->id,
                    'doc_cliente' => 'unique:alp_clientes,doc_cliente',
                    'id_type_doc' => 'required',
                    'telefono_cliente' => 'required|min:5',
                    'id_estructura_address' => 'required',
                    'principal_address' => 'required',
                    'secundaria_address' => 'required',
                    'edificio_address' => 'required',
                ];
            }
            default:
                break;
        }

    }


}

