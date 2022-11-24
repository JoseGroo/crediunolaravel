<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReestructuraPrestamoRequest extends FormRequest
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
            'prestamo_id'   => 'required|int',
            'cliente_id'    => 'required|int',
            'interes_id'    => 'required|int',
            'periodo'       => 'required|int',
            'taza_iva'      => 'required_if:aplico_taza_preferencial,==,1|numeric|nullable',
            'plazo'         => 'required_if:interes_id,,1,3,4|int|nullable',
            'dia_pago'      => 'required_if:periodo,2|int|nullable',
            'dia_descanso'  => 'different:dia_pago|required_if:periodo,1,2|int|nullable'
        ];
    }
}
