<?php

namespace App\Http\Requests;

use App\Rules\CampoUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class storeConcept extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::user()->id == 1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                'name' => 'required'/* , 'min:3', 'max:100',
                    new CampoUnique('concepts', 'name', null, true) */
                ,
                'default' => 'required'/* , 'integer', 'min:0', 'max:6',
                    ($this->default != 0) ? Rule::unique('concepts', 'default') : '', */
                
        ];
    }
}
