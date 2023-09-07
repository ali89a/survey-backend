<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'created_by' => ['required'],
//            'created_by' => ['required', 'numeric', 'exists:' . app(User::class)->getTable() . ',id'],
        ];
    }
    public function prepareForValidation()
    {
        $user = auth()->user();
        $this->merge([
            'created_by' => $user->id,
        ]);
    }

    public function messages()
    {
        return [
            'name.required' => 'Name Cannot be Empty',
        ];
    }

}
