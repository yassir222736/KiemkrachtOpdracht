<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valideert het publieke kassaticket-formulier.
 *
 * Regels:
 * - customer_name: verplicht, maximaal 255 tekens
 * - customer_email: verplicht, geldig e-mailadres, maximaal 255 tekens
 * - receipt: verplicht, alleen png/jpg/jpeg/pdf, maximaal 5MB
 */
class StoreTicketRequest extends FormRequest
{
    /**
     * Iedereen mag het publieke formulier indienen.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'receipt'        => ['required', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:5120'],
        ];
    }

    /**
     * Gebruiksvriendelijke attribuutnamen voor foutmeldingen.
     */
    public function attributes(): array
    {
        return [
            'customer_name'  => 'naam',
            'customer_email' => 'e-mailadres',
            'receipt'        => 'kassabon',
        ];
    }

    public function messages(): array
    {
        return [
            'receipt.mimes' => 'De :attribute moet een bestand zijn van het type: png, jpg of pdf.',
            'receipt.max'   => 'De :attribute mag niet groter zijn dan 5MB.',
        ];
    }
}
