<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalesRequest extends FormRequest
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
            'estado_id'             => 'required|int',
            'ciudad_id'             => 'required|int',
            'sucursal'              => 'required|max:100',
            'numero_contrato'       => 'max:100',
            'telefono'              => 'nullable|max:45|regex:/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/',
            'beneficiario'          => 'required|max:250',
            'dolar_compra'          => 'required|numeric',
            'dolar_venta'           => 'required|numeric',
            'euro_compra'           => 'required|numeric',
            'euro_venta'            => 'required|numeric',
            'dolar_moneda_compra'   => 'required|numeric',
            'dolar_moneda_venta'    => 'required|numeric',
        ];
    }
}
