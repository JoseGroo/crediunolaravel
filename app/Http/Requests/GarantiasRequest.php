<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GarantiasRequest extends FormRequest
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
            'tipo'          => 'required|int',
            'descripcion'   => 'required|max:250',
            'valor'         => 'required|numeric'
        ];
    }
}
