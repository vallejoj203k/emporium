<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\DocumentType;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $customers = Customer::with('documentType', 'creator')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    if (ctype_digit($search)) {
                        $innerQuery->orWhereKey((int) $search);
                    }

                    $likeSearch = '%' . $search . '%';

                    $innerQuery->orWhere('first_name', 'like', $likeSearch)
                        ->orWhere('last_name', 'like', $likeSearch)
                        ->orWhere('document_number', 'like', $likeSearch)
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$likeSearch]);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)->orderBy('name')->get();
        $plans = MembershipPlan::where('is_active', true)->orderBy('duration_days')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('name')->get();

        return view('customers.create', compact('documentTypes', 'plans', 'paymentMethods'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            $customer = Customer::create(collect($validated)->only([
                'document_type_id',
                'first_name',
                'last_name',
                'document_number',
                'birth_date',
                'gender',
                'phone',
                'emergency_contact_name',
                'emergency_contact_phone',
                'registered_at',
                'status',
                'observations',
            ])->all() + [
                'created_by' => $request->user()->id,
            ]);

            if (! empty($validated['membership_plan_id'])) {
                $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);
                $startDate = Carbon::parse($validated['start_date']);
                $endDate = $startDate->copy()->addDays(max($plan->duration_days - 1, 0));

                $membership = CustomerMembership::create([
                    'customer_id' => $customer->id,
                    'membership_plan_id' => $plan->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'registered_by' => $request->user()->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'paid_amount' => $validated['paid_amount'],
                    'status' => 'active',
                    'observations' => $validated['membership_observations'] ?? null,
                ]);

                Payment::create([
                    'customer_id' => $customer->id,
                    'customer_membership_id' => $membership->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'registered_by' => $request->user()->id,
                    'amount' => $validated['paid_amount'],
                    'payment_date' => $startDate,
                    'receipt_number' => $validated['receipt_number'] ?? null,
                    'observations' => $validated['membership_observations'] ?? null,
                ]);

                $membership->generateExpiryAlerts();
            }
        });

        $message = ! empty($validated['membership_plan_id'])
            ? 'Cliente registrado con su membresía y pago correctamente.'
            : 'Cliente registrado correctamente.';

        return redirect()->route('customers.index')->with('success', $message);
    }

    public function show(Customer $customer)
    {
        $customer->load('documentType', 'creator', 'memberships.plan', 'memberships.paymentMethod', 'payments.method', 'payments.user');

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $documentTypes = DocumentType::where('is_active', true)->orderBy('name')->get();

        return view('customers.edit', compact('customer', 'documentTypes'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
