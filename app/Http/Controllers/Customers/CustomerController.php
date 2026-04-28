<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::withCount(['invoices'])
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20|unique:customers',
            'email' => 'nullable|email|max:100|unique:customers',
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:50',
            'credit_limit' => 'numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $customer = Customer::create($data);
        return redirect()->route('customers.show', $customer)->with('success', 'Customer created.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['invoices' => fn($q) => $q->latest()->limit(10), 'repairJobs' => fn($q) => $q->latest()->limit(5), 'installments', 'loyaltyTransactions' => fn($q) => $q->latest()->limit(10)]);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:100|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:50',
            'credit_limit' => 'numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $customer->update($data);
        return redirect()->route('customers.show', $customer)->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }
}
