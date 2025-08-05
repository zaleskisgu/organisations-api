<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchAreaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'min_lat' => 'required|numeric|between:-90,90',
            'max_lat' => 'required|numeric|between:-90,90',
            'min_lng' => 'required|numeric|between:-180,180',
            'max_lng' => 'required|numeric|between:-180,180',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'min_lat.required' => 'Минимальная широта обязательна',
            'min_lat.numeric' => 'Минимальная широта должна быть числом',
            'min_lat.between' => 'Минимальная широта должна быть между -90 и 90',
            'max_lat.required' => 'Максимальная широта обязательна',
            'max_lat.numeric' => 'Максимальная широта должна быть числом',
            'max_lat.between' => 'Максимальная широта должна быть между -90 и 90',
            'min_lng.required' => 'Минимальная долгота обязательна',
            'min_lng.numeric' => 'Минимальная долгота должна быть числом',
            'min_lng.between' => 'Минимальная долгота должна быть между -180 и 180',
            'max_lng.required' => 'Максимальная долгота обязательна',
            'max_lng.numeric' => 'Максимальная долгота должна быть числом',
            'max_lng.between' => 'Максимальная долгота должна быть между -180 и 180',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }
} 