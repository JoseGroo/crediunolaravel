<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InteresesRequest extends FormRequest
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
            'nombre'                => 'required|max:200',
            'interes_mensual'       => 'required|numeric',
            'interes_diario'        => 'required|numeric',
            'otros_intereses'       => 'required|numeric',
            'iva'                   => 'required|numeric',
            'comision_apertura'     => 'required|numeric',
            'manejo_cuenta'         => 'required|numeric',
            'gastos_papeleria'      => 'required|numeric',
            'gastos_cobranza'       => 'required|numeric',
            'seguro_desempleo'      => 'required|numeric',
            'iva_otros_conceptos'   => 'required|numeric',
        ];
    }
}
