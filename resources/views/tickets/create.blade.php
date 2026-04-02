@extends('layouts.app')

@section('title', 'Kassabon Indienen')

@section('content')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold">Kassabon Indienen</h1>
        <p class="lead mb-0">Vul het formulier in en upload uw kassabon</p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Bevestigingsmelding na succesvolle upload --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Gelukt!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sluiten"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        {{-- Naam --}}
                        <div class="mb-3">
                            <label for="customer_name" class="form-label fw-medium">Naam <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="customer_name"
                                   name="customer_name"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name') }}"
                                   placeholder="Uw volledige naam"
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
                                   value="{{ old('customer_email') }}"
                                   placeholder="naam@voorbeeld.be"
                                   required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kassabon upload --}}
                        <div class="mb-4">
                            <label for="receipt" class="form-label fw-medium">Kassabon <span class="text-danger">*</span></label>
                            <input type="file"
                                   id="receipt"
                                   name="receipt"
                                   class="form-control @error('receipt') is-invalid @enderror"
                                   accept=".png,.jpg,.jpeg,.pdf"
                                   required>
                            <div class="form-text">Toegestane formaten: PNG, JPG of PDF (max. 5MB)</div>
                            @error('receipt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Verzendknop --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Kassabon Indienen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
