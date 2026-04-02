<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valideert het admin bewerkformulier voor kassatickets.
 *
 * Het kassabon-veld is optioneel bij bewerken — alleen valideren
 * als een nieuw bestand wordt geüpload.
 */
class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'receipt'        => ['nullable', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name'  => 'naam',
            'customer_email' => 'e-mailadres',
            'receipt'        => 'kassabon',
        ];
    }
}
