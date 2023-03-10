<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;

class InformacionLaboralAvalRequest extends FormRequest
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
            'aval_id'               => 'required|int',
            'empresa'               => 'required|max:150',
            'pais'                  => 'required|max:100',
            'estado_id'             => 'required|int',
            'localidad'             => 'required|max:100',
            'colonia'               => 'required|max:100',
            'numero_exterior'       => 'nullable|max:20',
            'calle'                 => 'nullable|max:100',
            'codigo_postal'         => 'nullable|max:10',
            'jefe_inmediato'        => 'required|max:200',
            'telefono'              => 'nullable|max:200',//|regex:/'.Config::get('constants.regexs.phone').'/
            'departamento'          => 'nullable|max:100',
            'antiguedad'            => 'required|int',
            'unidad_antiguedad'     => 'required|int',
        ];
    }
}
