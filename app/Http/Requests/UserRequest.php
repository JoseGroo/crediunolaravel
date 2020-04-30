<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules =  [
            'nombre'            => 'required|max:200',
            'apellido_paterno'  => 'required|max:200',
            'apellido_materno'  => 'max:200',
            'telefono'          => 'nullable|max:20|regex:/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/',
            'seguro_social'     => 'max:45',
            'sucursal_id'       => 'required|int',
            'usuario'           => 'required|max:250',
            'rol_id'            => 'required|int',

        ];

        if($this->route()->getName() != 'users.edit_post') {
            $rules['password'] = 'required';
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $rules;
    }
}
