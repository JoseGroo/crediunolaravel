<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FondosRequest extends FormRequest
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
            'fondo'                 => 'required|max:200',
            /*'tipo'                  => 'required|int',*/
            'banco'                 => 'required_if:tipo,==,1|max:200',
            'cuenta'                => 'required_if:tipo,==,1|max:200',
            'ultimo_cheque_girado'  => 'required_if:tipo,==,1|max:200',
            'importe_pesos'         => 'nullable|numeric',
            'importe_dolares'       => 'nullable|numeric',
            'importe_dolares_moneda'=> 'nullable|numeric',
            'importe_euros'         => 'nullable|numeric',
        ];
    }
}
//"sale_price" => "required_if:list_type,==,selling"