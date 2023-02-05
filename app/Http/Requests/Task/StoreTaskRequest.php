<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
            'board_id' => 'required|exists:boards,id',
            'list_id' => [
                'required',
                Rule::exists('list_boards', 'id')->where(function ($query) {
                    $query->where('board_id', request()->input('board_id'));
                })
            ],
            'title' => 'required|max:255',
        ];
    }
}
