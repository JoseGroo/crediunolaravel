<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConyugeAvalRequest extends FormRequest
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
            'nombre'            => 'required|max:100',
            'apellido_paterno'  => 'required|max:100',
            'apellido_materno'  => 'nullable|max:100',
        ];
    }
}
