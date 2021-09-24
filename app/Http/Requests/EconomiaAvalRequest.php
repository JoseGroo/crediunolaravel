<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EconomiaAvalRequest extends FormRequest
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
            'aval_id'           => 'required|int',
            'ingresos_propios'  => 'required|numeric',
            'ingresos_conyuge'  => 'nullable|numeric',
            'otros_ingresos'    => 'nullable|numeric',
            'gastos_fijos'      => 'required|numeric',
            'gastos_eventuales' => 'nullable|numeric',
        ];
    }
}
