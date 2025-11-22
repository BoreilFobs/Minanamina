@extends('layouts.app')

@section('title', 'Convertir en Cash - Minanamina')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Convertir en Cash</h1>
                <p class="text-muted mb-0">Transformez vos pièces en argent réel</p>
            </div>
            <a href="{{ route('rewards.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Current Balance Card -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-2">Votre Solde Actuel</h5>
                    <h2 class="mb-1" style="font-weight: 700; color: #0d6efd;">
                        {{ number_format($user->pieces_balance) }} <small>pièces</small>
                    </h2>
                    <p class="text-success mb-0">
                        ≈ {{ number_format($user->pieces_balance * $conversionRate, 0) }} CFA
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Info -->
    <div class="alert alert-info">
        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informations Importantes</h6>
        <ul class="mb-0">
            <li><strong>Taux de conversion:</strong> 1 pièce = {{ $conversionRate }} CFA</li>
            <li><strong>Montant minimum:</strong> {{ number_format($minimumConversion) }} pièces ({{ number_format($minimumConversion * $conversionRate, 0) }} CFA)</li>
            <li><strong>Délai de traitement:</strong> 1 à 3 jours ouvrables</li>
            <li><strong>Frais:</strong> Aucun frais de conversion</li>
        </ul>
    </div>

    <!-- Conversion Form -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Formulaire de Conversion</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('rewards.convert.submit') }}" id="conversionForm">
                        @csrf

                        <!-- Pieces Amount -->
                        <div class="mb-3">
                            <label for="pieces_amount" class="form-label">
                                Montant en Pièces <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('pieces_amount') is-invalid @enderror" 
                                   id="pieces_amount" 
                                   name="pieces_amount" 
                                   value="{{ old('pieces_amount') }}"
                                   min="{{ $minimumConversion }}"
                                   max="{{ $user->pieces_balance }}"
                                   step="100"
                                   required>
                            <small class="text-muted">
                                Minimum: {{ number_format($minimumConversion) }} | 
                                Maximum: {{ number_format($user->pieces_balance) }}
                            </small>
                            @error('pieces_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Cash Equivalent Display -->
                            <div class="mt-2 p-3 bg-light rounded">
                                <strong>Montant à recevoir:</strong>
                                <span id="cashAmount" class="text-success h5 mb-0">0 CFA</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">
                                Méthode de Paiement <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" 
                                    name="payment_method" 
                                    required>
                                <option value="">Sélectionnez...</option>
                                <option value="orange_money" {{ old('payment_method') == 'orange_money' ? 'selected' : '' }}>
                                    Orange Money
                                </option>
                                <option value="mtn_mobile_money" {{ old('payment_method') == 'mtn_mobile_money' ? 'selected' : '' }}>
                                    MTN Mobile Money
                                </option>
                                <option value="wave" {{ old('payment_method') == 'wave' ? 'selected' : '' }}>
                                    Wave
                                </option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                    Virement Bancaire
                                </option>
                                <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>
                                    PayPal
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Phone (for mobile money) -->
                        <div class="mb-3 payment-field" id="phone_field" style="display: none;">
                            <label for="payment_phone" class="form-label">
                                Numéro de Téléphone <span class="text-danger">*</span>
                            </label>
                            <input type="tel" 
                                   class="form-control @error('payment_phone') is-invalid @enderror" 
                                   id="payment_phone" 
                                   name="payment_phone" 
                                   value="{{ old('payment_phone', $user->phone) }}"
                                   placeholder="+221771234567">
                            <small class="text-muted">Format: +[code pays][numéro]</small>
                            @error('payment_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Email (for PayPal) -->
                        <div class="mb-3 payment-field" id="email_field" style="display: none;">
                            <label for="payment_email" class="form-label">
                                Adresse Email PayPal <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('payment_email') is-invalid @enderror" 
                                   id="payment_email" 
                                   name="payment_email" 
                                   value="{{ old('payment_email') }}"
                                   placeholder="votreemail@example.com">
                            @error('payment_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Account (for bank transfer) -->
                        <div class="mb-3 payment-field" id="account_field" style="display: none;">
                            <label for="payment_account" class="form-label">
                                Numéro de Compte Bancaire <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('payment_account') is-invalid @enderror" 
                                   id="payment_account" 
                                   name="payment_account" 
                                   value="{{ old('payment_account') }}"
                                   placeholder="Numéro de compte">
                            @error('payment_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms Confirmation -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte que cette conversion soit finale et que le délai de traitement soit de 1-3 jours ouvrables
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Soumettre la Demande
                            </button>
                            <a href="{{ route('rewards.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const piecesInput = document.getElementById('pieces_amount');
    const cashAmountDisplay = document.getElementById('cashAmount');
    const paymentMethodSelect = document.getElementById('payment_method');
    const conversionRate = {{ $conversionRate }};

    // Update cash amount calculation
    piecesInput.addEventListener('input', function() {
        const pieces = parseFloat(this.value) || 0;
        const cash = pieces * conversionRate;
        cashAmountDisplay.textContent = cash.toLocaleString('fr-FR', {maximumFractionDigits: 0}) + ' CFA';
    });

    // Show/hide payment fields based on method
    paymentMethodSelect.addEventListener('change', function() {
        // Hide all payment fields
        document.querySelectorAll('.payment-field').forEach(field => {
            field.style.display = 'none';
            field.querySelector('input').removeAttribute('required');
        });

        // Show relevant field
        const method = this.value;
        if (['orange_money', 'mtn_mobile_money', 'wave'].includes(method)) {
            document.getElementById('phone_field').style.display = 'block';
            document.getElementById('payment_phone').setAttribute('required', 'required');
        } else if (method === 'paypal') {
            document.getElementById('email_field').style.display = 'block';
            document.getElementById('payment_email').setAttribute('required', 'required');
        } else if (method === 'bank_transfer') {
            document.getElementById('account_field').style.display = 'block';
            document.getElementById('payment_account').setAttribute('required', 'required');
        }
    });

    // Trigger initial calculation if value exists
    if (piecesInput.value) {
        piecesInput.dispatchEvent(new Event('input'));
    }

    // Trigger payment method change if value exists
    if (paymentMethodSelect.value) {
        paymentMethodSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
