<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search and filter.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', '');

        $query = Customer::with('creator');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['Lead', 'Active', 'Inactive'], true)) {
            $query->where('status', $status);
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('customers.index', [
            'pageTitle' => 'Customers',
            'activeNav' => 'customers',
            'customers' => $customers,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('customers.create', [
            'pageTitle' => 'Add New Customer',
            'activeNav' => 'create_customer',
        ]);
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Lead', 'Active', 'Inactive'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['created_by'] = $request->user()->id;

        $customer = Customer::create($validated);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Customer "' . $customer->name . '" created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): View
    {
        $customer->load('creator');

        return view('customers.show', [
            'pageTitle' => $customer->name,
            'activeNav' => 'customers',
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'pageTitle' => 'Edit Customer: ' . $customer->name,
            'activeNav' => 'customers',
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Lead', 'Active', 'Inactive'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Customer "' . $customer->name . '" updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Request $request, Customer $customer): RedirectResponse
    {
        $user = $request->user();

        // Simple role check: Admin can delete any; Employee can delete only customers they created
        if (! $user->isAdmin() && $customer->created_by !== $user->id) {
            return redirect()->route('customers.index')
                ->with('error', 'You are not authorized to delete this customer.');
        }

        $customerName = $customer->name;
        $customer->delete();

        // If no customers remain, automatically reset the AUTO_INCREMENT counter so new records start from 1
        if (Customer::count() === 0 && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE customers AUTO_INCREMENT = 1');
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer "' . $customerName . '" was successfully deleted.');
    }
}
