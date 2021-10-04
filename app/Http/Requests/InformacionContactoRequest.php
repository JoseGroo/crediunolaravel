<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;

class InformacionContactoRequest extends FormRequest
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
            'telefono_fijo'             => 'nullable|max:200',//|regex:/'.Config::get('constants.regexs.phone').'/
            'telefono_movil'            => 'required|max:200',//|regex:/'.Config::get('constants.regexs.phone').'/
            'telefono_alternativo_1'    => 'required|max:200',//|regex:/'.Config::get('constants.regexs.phone').'/
            'nombre_alternativo_1'      => 'required|max:200',
            'parentesco_alternativo_1'  => 'required|max:40',
            'telefono_alternativo_2'    => 'nullable|max:200',//|regex:/'.Config::get('constants.regexs.phone').'/
            'nombre_alternativo_2'      => 'nullable|max:200',
            'parentesco_alternativo_2'  => 'nullable|max:40',
            'correo_electronico'        => 'nullable|max:150|regex:/'.Config::get('constants.regexs.email').'/',
        ];
    }
}
