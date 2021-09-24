<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentosAvalRequest extends FormRequest
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
            'aval_id'               => 'required|int',
            'documento'             => 'required|max:100',
            'tipo'                  => 'required|int',
            'clave_identificacion'  => 'max:100',
            'file'                  => 'required_if:documento_aval_id,<=,0|mimes:jpg,jpeg,png,pdf,gif|nullable',
        ];
    }
}
