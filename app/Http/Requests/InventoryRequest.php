<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class InventoryRequest extends FormRequest
{
    /**
     * السماح للجميع باستخدام هذا الريكويست.
     */
    public function authorize(): bool
    {
        return Auth::guard('user')->check();
    }

    /**
     * قواعد التحقق من البيانات.
     */
    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'type'             => 'required|string|max:255',
            'quantity'         => 'required|numeric|min:0',
            'unit'             => 'required|in:كغ,جرام,لتر,مل,كيس,علبة,قارورة,حبة',
            'purchase_date'    => 'nullable|date',
            'expiry_date'      => 'nullable|date|after_or_equal:purchase_date',
            'min_threshold'    => 'nullable|numeric|min:0',
            'supplier'         => 'nullable|string|max:255',
            'storage_location' => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
        ];
    }

    /**
     * رسائل الأخطاء المخصصة.
     */
    public function messages(): array
    {
        return [
            'name.required'          => 'اسم المادة الزراعية مطلوب.',
            'type.required'          => 'نوع المادة مطلوب.',
            'quantity.required'      => 'الكمية مطلوبة.',
            'unit.required'          => 'وحدة القياس مطلوبة.',
            'unit.in'                => 'وحدة القياس يجب أن تكون من القائمة المسموحة.',
            'expiry_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ الشراء.',
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
