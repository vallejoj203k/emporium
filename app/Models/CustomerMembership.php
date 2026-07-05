<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'membership_plan_id',
        'payment_method_id',
        'registered_by',
        'start_date',
        'end_date',
        'paid_amount',
        'status',
        'observations',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'paid_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'customer_membership_id');
    }

    public function generateExpiryAlerts(): void
    {
        $today = Carbon::today();
        $endDate = $this->end_date->copy();

        if ($endDate->isSameDay($today)) {
            $this->createAlert('expires_today', 'La membresía vence hoy.');
        }

        if ($endDate->isSameDay($today->copy()->addDays(3))) {
            $this->createAlert('expires_in_3_days', 'La membresía vence en 3 días.');
        }

        if ($endDate->lt($today)) {
            $this->createAlert('expired', 'La membresía ya está vencida.');
        }
    }

    private function createAlert(string $type, string $message): void
    {
        Alert::firstOrCreate(
            [
                'customer_id' => $this->customer_id,
                'customer_membership_id' => $this->id,
                'type' => $type,
            ],
            [
                'message' => $message,
                'generated_at' => now(),
                'is_read' => false,
            ]
        );
    }
}
