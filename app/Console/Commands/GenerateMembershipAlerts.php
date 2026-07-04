<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\Customer;
use App\Models\CustomerMembership;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMembershipAlerts extends Command
{
    protected $signature = 'gym:generate-alerts';

    protected $description = 'Genera alertas de vencimiento de membresías y actualiza estados';

    public function handle(): int
    {
        $today = Carbon::today();
        $threeDaysFromNow = Carbon::today()->addDays(3);

        $activeMemberships = CustomerMembership::with('customer')
            ->whereIn('status', ['active', 'expired'])
            ->get();

        foreach ($activeMemberships as $membership) {
            $endDate = $membership->end_date->copy();

            if ($endDate->isSameDay($today)) {
                Alert::firstOrCreate([
                    'customer_id' => $membership->customer_id,
                    'customer_membership_id' => $membership->id,
                    'type' => 'expires_today',
                ], [
                    'message' => 'La membresía vence hoy.',
                    'generated_at' => now(),
                    'is_read' => false,
                ]);
            }

            if ($endDate->isSameDay($threeDaysFromNow)) {
                Alert::firstOrCreate([
                    'customer_id' => $membership->customer_id,
                    'customer_membership_id' => $membership->id,
                    'type' => 'expires_in_3_days',
                ], [
                    'message' => 'La membresía vence en 3 días.',
                    'generated_at' => now(),
                    'is_read' => false,
                ]);
            }

            if ($endDate->lt($today)) {
                if ($membership->status !== 'expired') {
                    $membership->update(['status' => 'expired']);
                }

                Alert::firstOrCreate([
                    'customer_id' => $membership->customer_id,
                    'customer_membership_id' => $membership->id,
                    'type' => 'expired',
                ], [
                    'message' => 'La membresía ya está vencida.',
                    'generated_at' => now(),
                    'is_read' => false,
                ]);
            }
        }

        Customer::query()
            ->whereHas('memberships', fn($query) => $query->where('status', 'active'))
            ->update(['status' => 'active']);

        Customer::query()
            ->whereDoesntHave('memberships', fn($query) => $query->where('status', 'active'))
            ->whereHas('memberships')
            ->update(['status' => 'expired']);

        $this->info('Alertas de membresías generadas correctamente.');

        return self::SUCCESS;
    }
}
