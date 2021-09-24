<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;

class ContactosRequest extends FormRequest
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
            'nombre'                => 'required|max:300',
            'telefono'              => 'required|max:100',
            'correo_electronico'    => [
                'max:200',
                'regex:/'.Config::get('constants.regexs.email').'/',
                'nullable'
            ],
        ];
    }
}
