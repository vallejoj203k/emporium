<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerMembershipRequest;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerMembershipController extends Controller
{
    public function index()
    {
        $memberships = CustomerMembership::with('customer.documentType', 'plan', 'paymentMethod', 'registeredBy')
            ->latest()
            ->paginate(10);

        return view('customer-memberships.index', compact('memberships'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')->get();
        $plans = MembershipPlan::where('is_active', true)->orderBy('duration_days')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('name')->get();

        return view('customer-memberships.create', compact('customers', 'plans', 'paymentMethods'));
    }

    public function store(StoreCustomerMembershipRequest $request)
    {
        $validated = $request->validated();
        $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays(max($plan->duration_days - 1, 0));

        DB::transaction(function () use ($request, $validated, $plan, $startDate, $endDate) {
            $membership = CustomerMembership::create([
                'customer_id' => $validated['customer_id'],
                'membership_plan_id' => $plan->id,
                'payment_method_id' => $validated['payment_method_id'],
                'registered_by' => $request->user()->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'paid_amount' => $validated['paid_amount'],
                'status' => 'active',
                'observations' => $validated['observations'] ?? null,
            ]);

            Payment::create([
                'customer_id' => $validated['customer_id'],
                'customer_membership_id' => $membership->id,
                'payment_method_id' => $validated['payment_method_id'],
                'registered_by' => $request->user()->id,
                'amount' => $validated['paid_amount'],
                'payment_date' => $startDate,
                'receipt_number' => $validated['receipt_number'] ?? null,
                'observations' => $validated['observations'] ?? null,
            ]);

            Customer::whereKey($validated['customer_id'])->update(['status' => 'active']);

            $membership->generateExpiryAlerts();
        });

        return redirect()->route('customer-memberships.index')->with('success', 'Membresía registrada correctamente.');
    }

    public function show(CustomerMembership $customer_membership)
    {
        $customer_membership->load('customer.documentType', 'plan', 'paymentMethod', 'registeredBy', 'payments.method', 'payments.user');

        return view('customer-memberships.show', ['membership' => $customer_membership]);
    }
}
