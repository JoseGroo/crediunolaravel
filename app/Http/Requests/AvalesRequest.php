<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvalesRequest extends FormRequest
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
        $rules = [
            'sucursal_id'               => 'required|int',
            'nombre'                    => 'required|max:100',
            'apellido_paterno'          => 'required|max:100',
            'apellido_materno'          => 'nullable|max:100',
            'fecha_nacimiento'          => 'required|date_format:d/m/Y',
            'sexo'                      => 'required|int',
            'estado_civil'              => 'required|int',
            'ocupacion'                 => 'nullable|max:100',
            'calle'                     => 'nullable|max:100',
            'numero_exterior'           => 'nullable|max:20',
            'numero_interior'           => 'nullable|max:20',
            'colonia'                   => 'nullable|max:80',
            'entre_calles'              => 'nullable|max:200',
            'senas_particulares'        => 'nullable|max:200',
            'pais'                      => 'nullable|max:100',
            'estado_id'                 => 'nullable|int',
            'localidad'                 => 'nullable|max:100',
            'codigo_postal'             => 'nullable|max:10',
            'tiempo_residencia'         => 'nullable|int',
            'unidad_tiempo_residencia'  => 'nullable|int',
        ];

        if($this->route()->getName() == 'avales.create_post' || $this->route()->getName() == 'avales.create') {
            $rules['nombre_conyuge'] = 'required_if:estado_civil,==,2|required_if:estado_civil,==,4|max:100';
            $rules['apellido_paterno_conyuge'] = 'required_if:estado_civil,==,2|required_if:estado_civil,==,4|max:100';
            $rules['apellido_materno_conyuge'] = 'nullable|max:100';
        }

        return $rules;
    }
}
