<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EquipmentRequest extends FormRequest
{
        public function authorize(): bool
        {
            return Auth::guard('user')->check();
        }


    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'serial_number'     => 'nullable|string|max:255',
            'purchase_date'     => 'nullable|date',
            'last_maintenance'  => 'nullable|date',
            'next_maintenance'  => 'nullable|date',
            'status'            => 'required|in:نشطة,تحت الصيانة,معطلة',
            'type'              => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'usage_hours'       => 'nullable|in:0.5,1,1.5,2,2.5,3,3.5,4,4.5,5,6,7,8,9,10,12,24',
            'notes'             => 'nullable|string',
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
