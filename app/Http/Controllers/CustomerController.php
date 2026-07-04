<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\DocumentType;
use Illuminate\Http\Request;

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

        return view('customers.create', compact('documentTypes'));
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated() + [
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('customers.index')->with('success', 'Cliente registrado correctamente.');
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
