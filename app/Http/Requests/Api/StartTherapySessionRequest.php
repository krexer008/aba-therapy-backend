<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StartTherapySessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Пока разрешаем, потом добавим проверку роли
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'child_id' => 'required|integer|exists:children,id',
            'therapist_id' => 'required|integer|exists:users,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'selected_tms' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:1|max:60',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'Укажите ID ребенка',
            'child_id.exists' => 'Ребенок не найден',
            'therapist_id.required' => 'Укажите ID тераписта',
            'therapist_id.exists' => 'Терапист не найден',
        ];
    }
}
