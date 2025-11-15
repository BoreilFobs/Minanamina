<div class="card h-100 {{ $isEarned ? 'border-success' : 'border-secondary' }}" style="border-width: 2px;">
    <div class="card-body text-center">
        <!-- Badge Icon -->
        <div class="mb-3">
            <span style="font-size: 3rem; {{ $isEarned ? '' : 'filter: grayscale(100%); opacity: 0.5;' }}">
                {{ $badge->icon }}
            </span>
        </div>

        <!-- Badge Name -->
        <h5 class="card-title mb-2" style="font-weight: 600;">
            {{ $badge->name }}
        </h5>

        <!-- Badge Description -->
        <p class="card-text text-muted small">
            {{ $badge->description }}
        </p>

        @if($isEarned)
            <!-- Earned Badge -->
            <div class="badge bg-success mb-2">
                <i class="bi bi-check-circle"></i> Obtenu
            </div>
            @if($awardedAt)
            <div class="text-muted small">
                {{ $awardedAt->format('d/m/Y') }}
            </div>
            @endif
        @else
            <!-- Progress Bar for Unearned Badge -->
            @if(isset($progress) && $progress['percentage'] > 0)
            <div class="mb-2">
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-primary" 
                         role="progressbar" 
                         style="width: {{ $progress['percentage'] }}%"
                         aria-valuenow="{{ $progress['percentage'] }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $progress['percentage'] }}%
                    </div>
                </div>
                <small class="text-muted">
                    {{ $progress['current'] }} / {{ $progress['required'] }} {{ $progress['label'] }}
                </small>
            </div>
            @else
            <div class="badge bg-secondary">
                <i class="bi bi-lock"></i> Verrouillé
            </div>
            @endif
        @endif

        <!-- Points Reward -->
        @if($badge->points_reward > 0)
        <div class="mt-2">
            <span class="badge" style="background-color: #ffc107; color: #000;">
                <i class="bi bi-coin"></i> +{{ number_format($badge->points_reward) }} pièces
            </span>
        </div>
        @endif
    </div>
</div>
