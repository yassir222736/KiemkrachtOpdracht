<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Verwerkt het publieke kassaticket-formulier.
 *
 * Twee acties:
 * - create: toon het formulier
 * - store: verwerk de inzending
 */
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService
    ) {}

    /**
     * Toon het kassaticket-uploadformulier.
     */
    public function create(): View
    {
        return view('tickets.create');
    }

    /**
     * Verwerk het ingediende kassaticket.
     *
     * Bij succes: redirect terug met een bevestigingsmelding.
     * Bij validatiefout: Laravel redirect automatisch terug met foutmeldingen.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $validated = $request->safe()->only(['customer_name', 'customer_email']);
        $receipt = $request->file('receipt');

        $this->ticketService->createTicket($validated, $receipt);

        return redirect()
            ->route('tickets.create')
            ->with('success', 'Uw kassabon is succesvol ingediend! Bedankt.');
    }
}
