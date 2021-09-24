<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LimiteCreditoUpdateRequest extends FormRequest
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
            'estatus'           => 'required|int',
            'limite_credito'    => 'required|numeric'
        ];
    }
}
