@extends('layouts.modern')

@section('title', 'Convertir en Cash')

@section('content')
<!-- Back Header -->
<div class="back-header mb-3">
    <a href="{{ route('rewards.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Convertir en Cash</h5>
    <div style="width: 40px;"></div>
</div>

<!-- Balance Display -->
<div class="balance-display mb-4">
    <div class="balance-info">
        <span class="balance-label">Solde disponible</span>
        <div class="balance-value">
            <i class="bi bi-gem"></i> {{ number_format($user->pieces_balance) }}
        </div>
        <span class="balance-cash">≈ {{ number_format($user->pieces_balance * $conversionRate, 0) }} FCFA</span>
    </div>
</div>

<!-- Conversion Info -->
<div class="info-banner mb-4">
    <div class="info-row">
        <span><i class="bi bi-arrow-repeat"></i> Taux</span>
        <strong>1 pièce = {{ $conversionRate }} FCFA</strong>
    </div>
    <div class="info-row">
        <span><i class="bi bi-coin"></i> Minimum</span>
        <strong>{{ number_format($minimumConversion) }} pièces</strong>
    </div>
    <div class="info-row">
        <span><i class="bi bi-clock"></i> Délai</span>
        <strong>1-3 jours</strong>
    </div>
</div>

<!-- Conversion Form -->
<form method="POST" action="{{ route('rewards.convert.submit') }}" id="conversionForm">
    @csrf

    <!-- Amount Input -->
    <div class="form-section mb-4">
        <label class="form-label fw-bold">Montant à convertir</label>
        <div class="amount-input-wrapper">
            <input type="number" 
                   class="form-control amount-input @error('pieces_amount') is-invalid @enderror" 
                   id="pieces_amount" 
                   name="pieces_amount" 
                   value="{{ old('pieces_amount', $minimumConversion) }}"
                   min="{{ $minimumConversion }}"
                   max="{{ $user->pieces_balance }}"
                   step="100"
                   required>
            <span class="amount-suffix">pièces</span>
        </div>
        <div class="amount-shortcuts mt-2">
            <button type="button" class="shortcut-btn" onclick="setAmount({{ $minimumConversion }})">Min</button>
            <button type="button" class="shortcut-btn" onclick="setAmount({{ floor($user->pieces_balance * 0.25) }})">25%</button>
            <button type="button" class="shortcut-btn" onclick="setAmount({{ floor($user->pieces_balance * 0.5) }})">50%</button>
            <button type="button" class="shortcut-btn" onclick="setAmount({{ $user->pieces_balance }})">Max</button>
        </div>
        @error('pieces_amount')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Preview Card -->
    <div class="preview-card mb-4">
        <div class="preview-header">Vous recevrez</div>
        <div class="preview-amount" id="cashAmount">0 FCFA</div>
    </div>

    <!-- Payment Method -->
    <div class="form-section mb-4">
        <label class="form-label fw-bold">Mode de paiement</label>
        <div class="payment-methods">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="orange_money" {{ old('payment_method') == 'orange_money' ? 'checked' : '' }}>
                <div class="payment-card">
                    <div class="payment-icon orange">
                        <i class="bi bi-phone"></i>
                    </div>
                    <span>Orange Money</span>
                </div>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="mtn_mobile_money" {{ old('payment_method') == 'mtn_mobile_money' ? 'checked' : '' }}>
                <div class="payment-card">
                    <div class="payment-icon yellow">
                        <i class="bi bi-phone"></i>
                    </div>
                    <span>MTN MoMo</span>
                </div>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="wave" {{ old('payment_method') == 'wave' ? 'checked' : '' }}>
                <div class="payment-card">
                    <div class="payment-icon blue">
                        <i class="bi bi-phone"></i>
                    </div>
                    <span>Wave</span>
                </div>
            </label>
        </div>
        @error('payment_method')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone Number -->
    <div class="form-section mb-4" id="phone_field">
        <label class="form-label fw-bold">Numéro de téléphone</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" 
                   class="form-control @error('payment_phone') is-invalid @enderror" 
                   id="payment_phone" 
                   name="payment_phone" 
                   value="{{ old('payment_phone', $user->phone) }}"
                   placeholder="+221 77 123 45 67">
        </div>
        @error('payment_phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Terms -->
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label small" for="terms">
            J'accepte que cette conversion soit finale et que le délai de traitement soit de 1-3 jours ouvrables
        </label>
    </div>

    <!-- Submit Button -->
    <div class="submit-section">
        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
            <i class="bi bi-check-circle me-2"></i>Confirmer la conversion
        </button>
    </div>
</form>
@endsection

@push('styles')
<style>
/* Balance Display */
.balance-display {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 16px;
    padding: 1.25rem;
    color: white;
    text-align: center;
}

.balance-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

.balance-value {
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.balance-cash {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Info Banner */
.info-banner {
    background: white;
    border-radius: 14px;
    padding: 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.9rem;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row span {
    color: var(--muted);
}

.info-row i {
    margin-right: 6px;
}

/* Amount Input */
.amount-input-wrapper {
    position: relative;
}

.amount-input {
    padding-right: 70px;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    border-radius: 14px;
}

.amount-suffix {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.9rem;
}

.amount-shortcuts {
    display: flex;
    gap: 8px;
}

.shortcut-btn {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: white;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.shortcut-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Preview Card */
.preview-card {
    background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
    border-radius: 14px;
    padding: 1.25rem;
    text-align: center;
}

.preview-header {
    font-size: 0.85rem;
    color: #0f5132;
    margin-bottom: 0.25rem;
}

.preview-amount {
    font-size: 2rem;
    font-weight: 700;
    color: #0f5132;
}

/* Payment Methods */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.payment-option {
    margin: 0;
}

.payment-option input {
    display: none;
}

.payment-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem 0.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-option input:checked + .payment-card {
    border-color: var(--primary);
    background: rgba(102, 126, 234, 0.05);
}

.payment-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.2rem;
}

.payment-icon.orange {
    background: #ff7900;
    color: white;
}

.payment-icon.yellow {
    background: #ffcc00;
    color: #333;
}

.payment-icon.blue {
    background: #0084ff;
    color: white;
}

.payment-card span {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Submit Section */
.submit-section {
    position: sticky;
    bottom: 80px;
    padding: 1rem 0;
    background: linear-gradient(transparent, var(--light) 30%);
}

@media (min-width: 768px) {
    .submit-section {
        position: static;
        background: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const piecesInput = document.getElementById('pieces_amount');
    const cashAmountDisplay = document.getElementById('cashAmount');
    const conversionRate = {{ $conversionRate }};

    function updateCashAmount() {
        const pieces = parseInt(piecesInput.value) || 0;
        const cash = pieces * conversionRate;
        cashAmountDisplay.textContent = new Intl.NumberFormat('fr-FR').format(cash) + ' FCFA';
    }

    piecesInput.addEventListener('input', updateCashAmount);
    updateCashAmount();
});

function setAmount(amount) {
    document.getElementById('pieces_amount').value = amount;
    document.getElementById('pieces_amount').dispatchEvent(new Event('input'));
}
</script>
@endpush
