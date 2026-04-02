<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Vertegenwoordigt een ingediend kassaticket met klantgegevens en bijlage.
 *
 * @property int $id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $receipt_original_name
 * @property string $receipt_stored_name
 * @property string $receipt_mime_type
 * @property int $receipt_size
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Ticket extends Model
{
    /**
     * Expliciet opgegeven om mass-assignment kwetsbaarheden te voorkomen.
     */
    protected $fillable = [
        'customer_name',
        'customer_email',
        'receipt_original_name',
        'receipt_stored_name',
        'receipt_mime_type',
        'receipt_size',
    ];

    /**
     * Zoek tickets op naam of e-mailadres.
     * Gebruikt door de admin zoekfunctionaliteit.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->whereRaw('LOWER(customer_name) LIKE ?', ['%' . strtolower($term) . '%'])
              ->orWhereRaw('LOWER(customer_email) LIKE ?', ['%' . strtolower($term) . '%']);
        });
    }

    /**
     * Controleer of de bijlage een afbeelding is (voor inline weergave).
     */
    public function isReceiptImage(): bool
    {
        return str_starts_with($this->receipt_mime_type, 'image/');
    }

    /**
     * Geeft de bestandsgrootte in leesbaar formaat (bijv. "1.2 MB").
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->receipt_size;
        $units = ['B', 'KB', 'MB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }
}
