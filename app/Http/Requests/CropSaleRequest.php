<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CropSaleRequest extends FormRequest
{
        public function authorize(): bool
        {
            return Auth::guard('user')->check();
        }

    public function rules(): array
    {

        return [
        'farm_id'        => ['required','integer','exists:farms,id'],
        'crop_name'      => ['required','string','max:255'],
        'quantity'       => ['required','numeric','min:0'],
        'unit'           => ['required','in:كغ,طن,صندوق,ربطة,علبة'],
        'price_per_unit' => ['required','numeric','min:0'],
        'total_price'    => ['nullable','numeric','min:0'], // يُحسب إن لم يُرسل
        'sale_date'      => ['required','date'],
        'status'         => ['required','in:تم البيع,قيد البيع,محجوز'],
        'buyer_name'     => ['nullable','string','max:255'],
        'delivery_location' => ['nullable','string','max:255'],
        'notes'          => ['nullable','string'],
    ];
    }

    public function messages(): array
    {
        return [
            'farm_id.required' => 'حقل المزرعة مطلوب.',
            'farm_id.exists'   => 'المزرعة غير موجودة.',
            'crop_name.required' => 'اسم المحصول مطلوب.',
            'quantity.required'  => 'الكمية مطلوبة.',
            'quantity.min'       => 'الكمية يجب أن تكون أكبر من صفر.',
            'unit.in'            => 'وحدة القياس غير صحيحة.',
            'price_per_unit.required' => 'سعر الوحدة مطلوب.',
            'sale_date.required' => 'تاريخ البيع مطلوب.',
            'status.in'          => 'حالة البيع غير صحيحة.',
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
