@extends('layouts.app')

@section('title', 'Modifier le Profil - Minanamina')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Modifier le Profil</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Upload -->
                        <div class="mb-4 text-center bg-light p-4 rounded">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" id="avatar-preview" class="rounded-circle mb-3 border border-3 border-primary" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" id="avatar-placeholder" style="width: 120px; height: 120px; font-size: 3rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <img src="" alt="Avatar" id="avatar-preview" class="rounded-circle mb-3 d-none border border-3 border-primary" style="width: 120px; height: 120px; object-fit: cover;">
                            @endif
                            <div>
                                <label for="avatar" class="btn btn-primary">
                                    <i class="bi bi-camera"></i> Changer l'Avatar
                                </label>
                                <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
                                @error('avatar')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nom Complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone (Read-only) -->
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Numéro de Téléphone</label>
                            <input type="tel" class="form-control bg-light" id="phone" value="{{ $user->phone }}" readonly>
                            <small class="text-muted"><i class="bi bi-lock-fill"></i> Le numéro de téléphone ne peut pas être modifié</small>
                        </div>

                        <!-- Country -->
                        <div class="mb-3">
                            <label for="country" class="form-label fw-bold">Pays</label>
                            <select class="form-select @error('country') is-invalid @enderror" id="country" name="country">
                                <option value="">Sélectionner un pays</option>
                                <option value="SN" {{ old('country', $user->country) == 'SN' ? 'selected' : '' }}>🇸🇳 Sénégal</option>
                                <option value="CI" {{ old('country', $user->country) == 'CI' ? 'selected' : '' }}>🇨🇮 Côte d'Ivoire</option>
                                <option value="BF" {{ old('country', $user->country) == 'BF' ? 'selected' : '' }}>🇧🇫 Burkina Faso</option>
                                <option value="ML" {{ old('country', $user->country) == 'ML' ? 'selected' : '' }}>🇲🇱 Mali</option>
                                <option value="CM" {{ old('country', $user->country) == 'CM' ? 'selected' : '' }}>🇨🇲 Cameroun</option>
                                <option value="GN" {{ old('country', $user->country) == 'GN' ? 'selected' : '' }}>🇬🇳 Guinée</option>
                                <option value="BJ" {{ old('country', $user->country) == 'BJ' ? 'selected' : '' }}>🇧🇯 Bénin</option>
                                <option value="TG" {{ old('country', $user->country) == 'TG' ? 'selected' : '' }}>🇹🇬 Togo</option>
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
