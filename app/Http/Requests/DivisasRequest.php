<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DivisasRequest extends FormRequest
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
            'sucursal_id'   => 'required_without:ciudad_id|nullable|int',
            'ciudad_id'     => 'required_without:sucursal_id|nullable|int',
            'divisa_compra' => 'required|numeric',
            'divisa_venta'  => 'required|numeric',
            'iva_divisa'    => 'required|numeric'
        ];
    }
}
