<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class FinancialRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('user')->check();
    }

    public function rules(): array
    {
        return [
            'direction' => ['required', Rule::in(['دخل', 'نفقات'])],
            'category'  => ['required', Rule::in([
                'دعم زراعي',
                'أسمدة',
                'مبيدات',
                'بذور',
                'وقود',
                'مياه',
                'صيانة',
                'معدات',
                'عمالة',
                'أخرى',
            ])],
            'amount'           => ['required', 'numeric', 'min:0'],
            'date'             => ['required', 'date'],
            'description'      => ['nullable', 'string'],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'direction.required' => 'حقل الاتجاه (دخل/نفقات) مطلوب.',
            'direction.in'       => 'قيمة الاتجاه يجب أن تكون إمّا "دخل" أو "نفقات".',

            'category.required'  => 'حقل التصنيف مطلوب.',
            'category.in'        => 'قيمة التصنيف غير صحيحة.',

            'amount.required'    => 'حقل المبلغ مطلوب.',
            'amount.numeric'     => 'المبلغ يجب أن يكون رقمًا.',
            'amount.min'         => 'المبلغ لا يمكن أن يكون سالبًا.',

            'date.required'      => 'حقل التاريخ مطلوب.',
            'date.date'          => 'صيغة التاريخ غير صحيحة.',
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
