<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;

class ReferenciasClienteRequest extends FormRequest
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
        return [
            'cliente_id'                => 'required|int',
            'nombre'                    => 'required|max:100',
            'apellido_paterno'          => 'required|max:100',
            'apellido_materno'          => 'max:100',
            'telefono_fijo'             => [
                'max:300',
                /*'regex:/'.Config::get('constants.regexs.phone').'/',*/
                'nullable'
            ],
            'telefono_movil'            => [
                'max:300',
                /*'regex:/'.Config::get('constants.regexs.phone').'/',*/
                'required'
            ],
            'telefono_oficina'          => [
                'max:300',
                /*'regex:/'.Config::get('constants.regexs.phone').'/',*/
                'nullable'
            ],
            'correo_electronico'        => [
                'max:250',
                'regex:/'.Config::get('constants.regexs.email').'/',
                'nullable'
            ],
            'calle'                     => 'max:200',
            'numero_exterior'           => 'max:45',
            'colonia'                   => 'max:100',
            'tiempo_conocerlo'          => 'required|int',
            'unidad_tiempo_conocerlo'   => 'required|int',
            'relacion'                  => 'required|max:100',
        ];
    }
}
