<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'bank' => ['required', 'string', 'in:kbank,scb,bbl,ktb,bay,ttb,gsb,baac'],
            'account_number' => ['required', 'string', 'min:10', 'max:15', 'regex:/^\d+$/'],
            'account_name' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => 'จำนวนเงิน',
            'bank' => 'ธนาคาร',
            'account_number' => 'เลขที่บัญชี',
            'account_name' => 'ชื่อเจ้าของบัญชี',
            'note' => 'หมายเหตุ',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'กรุณาระบุจำนวนเงินที่ต้องการถอน',
            'amount.numeric' => 'จำนวนเงินต้องเป็นตัวเลขเท่านั้น',
            'amount.min' => 'จำนวนเงินขั้นต่ำคือ ฿1.00',
            'amount.max' => 'จำนวนเงินสูงสุดคือ ฿999,999.99',
            'bank.required' => 'กรุณาเลือกธนาคารปลายทาง',
            'bank.in' => 'ธนาคารที่เลือกไม่ถูกต้อง',
            'account_number.required' => 'กรุณากรอกเลขที่บัญชีปลายทาง',
            'account_number.min' => 'เลขที่บัญชีต้องมีอย่างน้อย 10 หลัก',
            'account_number.max' => 'เลขที่บัญชีต้องไม่เกิน 15 หลัก',
            'account_number.regex' => 'เลขที่บัญชีต้องเป็นตัวเลขเท่านั้น',
            'account_name.required' => 'กรุณากรอกชื่อเจ้าของบัญชี',
            'account_name.max' => 'ชื่อเจ้าของบัญชีต้องไม่เกิน 100 ตัวอักษร',
            'note.max' => 'หมายเหตุต้องไม่เกิน 255 ตัวอักษร',
        ];
    }
}
