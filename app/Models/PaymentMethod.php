<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'description',
        'configuration',
        'is_active',
        'display_order',
        'minimum_amount',
        'maximum_amount',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
    ];

    protected function casts(): array
    {
        return [
            'configuration' => 'json',
            'is_active' => 'boolean',
            'minimum_amount' => 'decimal:2',
            'maximum_amount' => 'decimal:2',
            'transaction_fee_percentage' => 'decimal:2',
            'transaction_fee_fixed' => 'decimal:2',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
