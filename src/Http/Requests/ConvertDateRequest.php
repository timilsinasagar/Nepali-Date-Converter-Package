<?php
// src/Http/Requests/ConvertDateRequest.php

namespace Sagartimilsina\NepaliDate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertDateRequest extends FormRequest
{
    /**
     * Anyone can use the demo conversion form -- adjust if you mount this
     * behind auth in your own app.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'direction' => ['required', 'string', 'in:ad_to_bs,bs_to_ad'],
            'date_value' => ['required', 'string', 'max:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'direction.required' => 'Please choose a conversion direction.',
            'direction.in' => 'Conversion direction must be either AD to BS or BS to AD.',
            'date_value.required' => 'Please enter a date to convert.',
            'date_value.max' => 'That date value is too long to be valid.',
        ];
    }

    /**
     * Named accessors so the controller/service layer never has to
     * reach for ->input() with raw string keys.
     */
    public function direction(): string
    {
        return $this->validated('direction');
    }

    public function dateValue(): string
    {
        return trim($this->validated('date_value'));
    }
}
