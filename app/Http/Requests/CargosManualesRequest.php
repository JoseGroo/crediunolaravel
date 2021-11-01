<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargosManualesRequest extends FormRequest
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
            'cliente_id'    => 'required|int',
            'prestamo_id'   => 'required|int',
            'adeudo_id'     => 'required|int',
            'importe'       => 'required|numeric',
            'comentario'    => 'required|max:250'
        ];
    }
}
