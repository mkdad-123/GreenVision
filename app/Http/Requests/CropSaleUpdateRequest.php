<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CropSaleUpdateRequest extends FormRequest
{
         public function authorize(): bool
        {
            return Auth::guard('user')->check();
        }

    public function rules(): array
    {
        return [
            'farm_id'         => 'sometimes|exists:farms,id',
            'crop_name'       => 'sometimes|string|max:255',
            'quantity'        => 'sometimes|numeric|min:0',
            'unit'            => 'sometimes|in:كغ,طن,صندوق,ربطة,علبة',
            'price_per_unit'  => 'sometimes|numeric|min:0',
            'total_price'     => 'sometimes|numeric|min:0',
            'sale_date'       => 'sometimes|date',
            'status'          => 'sometimes|in:تم البيع,قيد البيع,محجوز',
            'buyer_name'      => 'sometimes|nullable|string|max:255',
            'delivery_location'=> 'sometimes|nullable|string|max:255',
            'notes'           => 'sometimes|nullable|string',
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
