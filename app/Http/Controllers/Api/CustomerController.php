<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     * GET /api/customers
     */
    public function index(Request $request): JsonResponse
    {
        $query = Customer::with('creator');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $customers = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'count' => $customers->count(),
            'data' => $customers,
        ], 200);
    }

    /**
     * Display the specified customer.
     * GET /api/customers/{id}
     */
    public function show(string|int $id): JsonResponse
    {
        $customer = Customer::with('creator')->find($id);

        if (! $customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ], 200);
    }

    /**
     * Store a newly created customer in storage.
     * POST /api/customers
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['Lead', 'Active', 'Inactive'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Assign created_by to authenticated user or default first user
        $data['created_by'] = $request->user()?->id ?? User::first()?->id ?? 1;
        $data['status'] = $data['status'] ?? 'Lead';

        $customer = Customer::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer created successfully.',
            'data' => $customer,
        ], 201);
    }

    /**
     * Update the specified customer in storage.
     * PUT/PATCH /api/customers/{id}
     */
    public function update(Request $request, string|int $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (! $customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(['Lead', 'Active', 'Inactive'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully.',
            'data' => $customer,
        ], 200);
    }

    /**
     * Remove the specified customer from storage.
     * DELETE /api/customers/{id}
     */
    public function destroy(string|int $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (! $customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found.',
            ], 404);
        }

        $customer->delete();

        // If no customers remain, automatically reset the AUTO_INCREMENT counter so new records start from 1
        if (Customer::count() === 0 && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE customers AUTO_INCREMENT = 1');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully.',
        ], 200);
    }
}
