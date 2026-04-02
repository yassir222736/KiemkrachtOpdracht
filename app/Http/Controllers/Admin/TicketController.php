<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Beheerder-controller voor het beheren van ingediende kassatickets.
 *
 * Alle routes zijn beschermd door 'auth' en 'admin' middleware.
 */
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService
    ) {}

    /**
     * Toon alle tickets met zoek- en pagineerfunctionaliteit.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $tickets = $this->ticketService->getPaginatedTickets($search);

        return view('admin.tickets.index', compact('tickets', 'search'));
    }

    /**
     * Toon de details van een enkel ticket.
     */
    public function show(Ticket $ticket): View
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Toon het bewerkformulier voor een ticket.
     */
    public function edit(Ticket $ticket): View
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Werk een ticket bij.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->safe()->only(['customer_name', 'customer_email']);
        $receipt = $request->file('receipt');

        $this->ticketService->updateTicket($ticket, $validated, $receipt);

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Ticket succesvol bijgewerkt.');
    }

    /**
     * Serveer een bonbestand veilig.
     *
     * Bestanden worden opgeslagen buiten de publieke map.
     * Deze actie streamt het bestand via PHP met juiste headers.
     */
    public function downloadReceipt(Ticket $ticket): BinaryFileResponse
    {
        $path = $this->ticketService->getReceiptPath($ticket->receipt_stored_name);

        if (!$path) {
            abort(404, 'Bestand niet gevonden.');
        }

        return response()->file($path, [
            'Content-Type' => $ticket->receipt_mime_type,
            'Content-Disposition' => 'inline; filename="' . $ticket->receipt_original_name . '"',
        ]);
    }
}
