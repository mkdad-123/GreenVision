<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('user')->check();
    }

    public function rules(): array
    {
        return [
            'farm_id' => 'required|exists:farms,id',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'repeat_interval' => 'nullable|string|max:255',
            'status' => 'nullable|in:قيد التنفيذ,منجزة,مؤجلة',
            'priority' => 'nullable|in:عالية,متوسطة,منخفضة',
        ];
    }

    public function messages(): array
    {
        return [
            'farm_id.required' => 'يجب اختيار المزرعة المرتبطة بالمهمة.',
            'farm_id.exists' => 'المزرعة غير موجودة.',
            'type.required' => 'نوع المهمة مطلوب.',
            'date.required' => 'تاريخ المهمة مطلوب.',
            'date.date' => 'صيغة التاريخ غير صحيحة.',
            'status.in' => 'الحالة يجب أن تكون إما: قيد التنفيذ، منجزة، أو مؤجلة.',
            'priority.in' => 'الأولوية يجب أن تكون: عالية، متوسطة، أو منخفضة.',
        ];
    }


        protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'ok' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
