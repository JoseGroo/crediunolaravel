<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;

class HistorialClienteRequest extends FormRequest
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
            'acreedor'      => 'required_if:tiene_adeudo,==,1|nullable|max:100',
            'telefono'      => 'required_if:tiene_adeudo,==,1|nullable|max:200',//|regex:/'.Config::get('constants.regexs.phone').'/
            'adeudo'        => 'required_if:tiene_adeudo,==,1|nullable|numeric',
        ];
    }
}
