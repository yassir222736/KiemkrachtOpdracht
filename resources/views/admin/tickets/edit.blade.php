@extends('layouts.app')

@section('title', 'Ticket Bewerken #' . $ticket->id)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Ticket #{{ $ticket->id }} Bewerken</h2>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">Terug</a>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Naam --}}
                        <div class="mb-3">
                            <label for="customer_name" class="form-label fw-medium">Naam <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="customer_name"
                                   name="customer_name"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', $ticket->customer_name) }}"
                                   required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- E-mailadres --}}
                        <div class="mb-3">
                            <label for="customer_email" class="form-label fw-medium">E-mailadres <span class="text-danger">*</span></label>
                            <input type="email"
                                   id="customer_email"
                                   name="customer_email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email', $ticket->customer_email) }}"
                                   required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Huidig bestand --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium">Huidige kassabon</label>
                            <div class="p-3 bg-light rounded">
                                <span class="badge badge-file me-2">
                                    {{ strtoupper(pathinfo($ticket->receipt_original_name, PATHINFO_EXTENSION)) }}
                                </span>
                                {{ $ticket->receipt_original_name }}
                                <small class="text-muted">({{ $ticket->formatted_file_size }})</small>
                                <a href="{{ route('admin.tickets.receipt', $ticket) }}" class="ms-2" target="_blank">Bekijken</a>
                            </div>
                        </div>

                        {{-- Nieuw bestand (optioneel) --}}
                        <div class="mb-4">
                            <label for="receipt" class="form-label fw-medium">Nieuwe kassabon (optioneel)</label>
                            <input type="file"
                                   id="receipt"
                                   name="receipt"
                                   class="form-control @error('receipt') is-invalid @enderror"
                                   accept=".png,.jpg,.jpeg,.pdf">
                            <div class="form-text">Laat leeg om het huidige bestand te behouden. Toegestane formaten: PNG, JPG of PDF (max. 5MB)</div>
                            @error('receipt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Knoppen --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Opslaan</button>
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">Annuleren</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
