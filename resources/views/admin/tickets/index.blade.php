@extends('layouts.app')

@section('title', 'Beheer — Tickets')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Ingediende Tickets</h2>
        <span class="badge bg-secondary fs-6">{{ $tickets->total() }} totaal</span>
    </div>

    {{-- Zoekbalk --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label for="search" class="form-label fw-medium">Zoeken op naam of e-mail</label>
                    <input type="text"
                           id="search"
                           name="search"
                           class="form-control"
                           placeholder="Zoek op naam of e-mailadres..."
                           value="{{ $search }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Zoeken</button>
                    @if($search)
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Bevestigingsmelding --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sluiten"></button>
        </div>
    @endif

    {{-- Tickets tabel --}}
    @if($tickets->count() > 0)
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Naam</th>
                            <th>E-mail</th>
                            <th>Bestand</th>
                            <th>Grootte</th>
                            <th>Ingediend op</th>
                            <th class="text-end">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->customer_name }}</td>
                                <td>
                                    <a href="mailto:{{ $ticket->customer_email }}">{{ $ticket->customer_email }}</a>
                                </td>
                                <td>
                                    <span class="badge badge-file">
                                        {{ strtoupper(pathinfo($ticket->receipt_original_name, PATHINFO_EXTENSION)) }}
                                    </span>
                                    <small class="text-muted">{{ $ticket->receipt_original_name }}</small>
                                </td>
                                <td><small>{{ $ticket->formatted_file_size }}</small></td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                       class="btn btn-sm btn-outline-primary" title="Bekijken">
                                        Bekijken
                                    </a>
                                    <a href="{{ route('admin.tickets.edit', $ticket) }}"
                                       class="btn btn-sm btn-outline-warning" title="Bewerken">
                                        Bewerken
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginering --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-0">
                    @if($search)
                        Geen tickets gevonden voor "{{ $search }}".
                    @else
                        Er zijn nog geen tickets ingediend.
                    @endif
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
