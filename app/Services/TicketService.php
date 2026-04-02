<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Bevat alle business logic voor het beheren van kassatickets.
 *
 * Verantwoordelijkheden:
 * - Aanmaken van tickets met bestandsverwerking
 * - Bijwerken van tickets (met optionele bestandsvervanging)
 * - Genereren van unieke bestandsnamen (UUID-gebaseerd)
 * - Ophalen van tickets met zoek- en pagineerfunctionaliteit
 * - Veilig serveren van opgeslagen bestanden
 */
class TicketService
{
    private const DISK = 'receipts';

    /**
     * Maak een nieuw ticket aan op basis van gevalideerde formulierdata.
     */
    public function createTicket(array $validated, UploadedFile $receipt): Ticket
    {
        $fileData = $this->storeReceipt($receipt);

        return Ticket::create(array_merge($validated, $fileData));
    }

    /**
     * Werk een bestaand ticket bij.
     *
     * Als een nieuw bestand wordt meegestuurd, wordt het oude bestand
     * verwijderd en vervangen door het nieuwe.
     */
    public function updateTicket(Ticket $ticket, array $validated, ?UploadedFile $receipt = null): Ticket
    {
        $data = $validated;

        if ($receipt) {
            $this->deleteReceiptFile($ticket->receipt_stored_name);
            $data = array_merge($data, $this->storeReceipt($receipt));
        }

        $ticket->update($data);

        return $ticket->fresh();
    }

    /**
     * Haal gepagineerde tickets op, optioneel gefilterd op zoekterm.
     */
    public function getPaginatedTickets(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Ticket::search($search)
            ->orderBy('created_at')
            ->paginate($perPage)
            ->appends(['search' => $search]);
    }

    /**
     * Geef het volledige opslagpad van een bonbestand terug.
     * Wordt gebruikt door de controller om bestanden veilig te serveren.
     */
    public function getReceiptPath(string $storedName): ?string
    {
        if (!Storage::disk(self::DISK)->exists($storedName)) {
            return null;
        }

        return Storage::disk(self::DISK)->path($storedName);
    }

    /**
     * Sla een bonbestand op met een unieke UUID-gebaseerde naam.
     *
     * UUID voorkomt naamconflicten en maakt bestandsnamen
     * onvoorspelbaar (bescherming tegen enumeration attacks).
     */
    private function storeReceipt(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();
        $uniqueName = Str::uuid() . '.' . $extension;

        $file->storeAs('', $uniqueName, self::DISK);

        return [
            'receipt_original_name' => $file->getClientOriginalName(),
            'receipt_stored_name'   => $uniqueName,
            'receipt_mime_type'     => $file->getMimeType(),
            'receipt_size'          => $file->getSize(),
        ];
    }

    /**
     * Verwijder een bonbestand van de opslag.
     */
    private function deleteReceiptFile(string $storedName): void
    {
        Storage::disk(self::DISK)->delete($storedName);
    }
}
