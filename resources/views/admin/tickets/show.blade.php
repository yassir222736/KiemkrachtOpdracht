@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Ticket #{{ $ticket->id }}</h2>
                <div>
                    <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-warning">Bewerken</a>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">Terug</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Naam</dt>
                        <dd class="col-sm-8">{{ $ticket->customer_name }}</dd>

                        <dt class="col-sm-4">E-mailadres</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:{{ $ticket->customer_email }}">{{ $ticket->customer_email }}</a>
                        </dd>

                        <dt class="col-sm-4">Ingediend op</dt>
                        <dd class="col-sm-8">{{ $ticket->created_at->format('d/m/Y H:i:s') }}</dd>

                        <dt class="col-sm-4">Laatst bijgewerkt</dt>
                        <dd class="col-sm-8">{{ $ticket->updated_at->format('d/m/Y H:i:s') }}</dd>

                        <dt class="col-sm-4">Bestandsnaam</dt>
                        <dd class="col-sm-8">{{ $ticket->receipt_original_name }}</dd>

                        <dt class="col-sm-4">Bestandstype</dt>
                        <dd class="col-sm-8">{{ $ticket->receipt_mime_type }}</dd>

                        <dt class="col-sm-4">Bestandsgrootte</dt>
                        <dd class="col-sm-8">{{ $ticket->formatted_file_size }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Kassabon weergave --}}
            <div class="card">
                <div class="card-header fw-medium">Kassabon</div>
                <div class="card-body text-center">
                    @if($ticket->isReceiptImage())
                        <img src="{{ route('admin.tickets.receipt', $ticket) }}"
                             alt="Kassabon"
                             class="img-fluid rounded"
                             style="max-height: 500px;">
                    @else
                        <div class="py-4">
                            <p class="text-muted mb-3">PDF-bestand kan niet inline worden weergegeven.</p>
                            <a href="{{ route('admin.tickets.receipt', $ticket) }}"
                               class="btn btn-primary" target="_blank">
                                PDF Openen
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
