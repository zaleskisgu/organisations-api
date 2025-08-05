<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRadiusRequest extends FormRequest
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
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:0',
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
            'latitude.required' => 'Широта обязательна',
            'latitude.numeric' => 'Широта должна быть числом',
            'latitude.between' => 'Широта должна быть между -90 и 90',
            'longitude.required' => 'Долгота обязательна',
            'longitude.numeric' => 'Долгота должна быть числом',
            'longitude.between' => 'Долгота должна быть между -180 и 180',
            'radius.required' => 'Радиус обязателен',
            'radius.numeric' => 'Радиус должен быть числом',
            'radius.min' => 'Радиус должен быть больше 0',
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