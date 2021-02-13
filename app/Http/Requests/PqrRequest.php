<?php

namespace App\Http\Requests;

use App\Rules\Captcha;

use Illuminate\Foundation\Http\FormRequest;

class PqrRequest extends FormRequest
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
                    'nombre_pqr.required' => 'El Nombre es requerido',
                    'nombre_pqr.min' => 'El Nombre debe tener como mínimo 3 caracteres',
                    'nombre_pqr.regex' => 'El Nombre contiene caracteres invalidos',
                    'apellido_pqr.required' => 'El Apellido es requerido',
                    'apellido_pqr.min' => 'El Apellido debe tener como mínimo 3 caracteres',
                    'apellido_pqr.regex' => 'El Apellido contiene caracteres invalidos',
                    'tdocume_pqr.required' => 'El Tipo de Documento es requerido',
                    'identificacion_pqr.required' => 'La Identificación es requerida',
                    'identificacion_pqr.regex' => 'La Identificación contiene caracteres invalidos',
                    'email_pqr.required' => 'El Email es requerido',
                    'celular_pqr.required' => 'El Teléfono o Célular es requerido',
                    'celular_pqr.numeric' => 'El Teléfono o Célular debe ser numérico',
                    'celular_pqr.min' => 'El Teléfono o Célular debe tener mínimo 6 caracteres',
                    'pais_pqr.required' => 'El Pais es requerido',
                    'ciudad_pqr.required' => 'La Ciudad es requerida',
                    'ciudad_pqr.min' => 'La Ciudad debe tener como mínimo 3 caracteres',
                    'ciudad_pqr.regex' => 'La Ciudad contiene caracteres invalidos',
                    'tipo_pqr.required' => 'El Tipo de contacto es requerido',
                    'mensaje_pqr.required' => 'El Mensaje es requerido',
                    'mensaje_pqr.min' => 'El Mensaje debe tener como mínimo 20 caracteres',
                    'mensaje_pqr.regex' => 'El Mensaje es requerido,  contiene caracteres invalidos',
                    'habeas_cliente.required' => 'La Autorizacion de tratamiento de datos y aceptación de términos es requerido',
                    'g-recaptcha-response' => new Captcha()
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
                    'nombre_pqr' => 'required|min:3|regex:/^[A-Za-z0-9\s]+$/',
                    'apellido_pqr' => 'required|min:3|regex:/^[A-Za-z0-9\s]+$/',
                    'tdocume_pqr' => 'required',
                    'identificacion_pqr' => 'required|min:5|regex:/^[A-Za-z0-9\s]+$/',
                    'email_pqr' => 'required|email',
                    'celular_pqr' => 'required|numeric|min:6',
                    'pais_pqr' => 'required',
                    'ciudad_pqr' => 'required|min:3|regex:/^[A-Za-z0-9\s]+$/',
                    'tipo_pqr' => 'required',
                    'mensaje_pqr' => 'required|min:10|max:300|regex:/^[A-Za-z0-9\s]+$/',
                    'habeas_cliente' => 'required',
                    'file_update' => 'mimes:jpg,png,jpeg,pdf|max:5120',

                    'g-recaptcha-response' => new Captcha()
                ];
            }
            default:
                break;
        }

    }


}

