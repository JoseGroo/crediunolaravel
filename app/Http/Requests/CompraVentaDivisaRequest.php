<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraVentaDivisaRequest extends FormRequest
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
            'divisa_id'     => 'required|int',
            'movimiento'    => 'required|int',
            'cantidad'      => 'required|numeric'
        ];
    }
}
