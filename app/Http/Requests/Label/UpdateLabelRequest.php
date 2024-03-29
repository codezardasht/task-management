<?php

namespace App\Http\Requests\Label;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLabelRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('labels')->ignore($this->label->id),
            ],
            'color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ];
    }
}
