<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CrmTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@crm.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->employee = User::create([
            'name' => 'Employee User',
            'email' => 'employee@crm.local',
            'password' => Hash::make('password123'),
            'role' => 'employee',
        ]);
    }

    /**
     * 1. Test unauthenticated guest redirection.
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/customers');
        $response->assertRedirect('/login');
    }

    /**
     * 2. Test login page displays correctly.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign in to your account');
    }

    /**
     * 3. Test successful authentication.
     */
    public function test_user_can_authenticate(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@crm.local',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect('/dashboard');
    }

    /**
     * 4. Test failed authentication with invalid credentials.
     */
    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@crm.local',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * 5. Test logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $response = $this->actingAs($this->admin)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    /**
     * 6. Test dashboard metrics and recent customers.
     */
    public function test_dashboard_displays_correct_metrics(): void
    {
        Customer::create([
            'name' => 'Lead Client',
            'email' => 'lead@test.com',
            'status' => 'Lead',
            'created_by' => $this->admin->id,
        ]);

        Customer::create([
            'name' => 'Active Client',
            'email' => 'active@test.com',
            'status' => 'Active',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Lead Client');
        $response->assertSee('Active Client');
    }

    /**
     * 7a. Test customer create form renders.
     */
    public function test_authenticated_user_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/customers/create');

        $response->assertStatus(200);
        $response->assertSee('Add New Customer');
    }

    /**
     * 7b. Test customer creation via web form.
     */
    public function test_authenticated_user_can_create_customer(): void
    {
        $response = $this->actingAs($this->admin)->post('/customers', [
            'name' => 'New Customer Inc',
            'email' => 'contact@newcustomer.com',
            'phone' => '+1-555-1234',
            'company' => 'New Customer Inc',
            'status' => 'Active',
            'notes' => 'Some initial notes',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'New Customer Inc',
            'email' => 'contact@newcustomer.com',
            'created_by' => $this->admin->id,
        ]);

        $customer = Customer::where('email', 'contact@newcustomer.com')->first();
        $response->assertRedirect("/customers/{$customer->id}");
    }

    /**
     * 8. Test customer details and editing.
     */
    public function test_authenticated_user_can_view_and_update_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Original Name',
            'email' => 'original@test.com',
            'status' => 'Lead',
            'created_by' => $this->admin->id,
        ]);

        // View details
        $viewResponse = $this->actingAs($this->admin)->get("/customers/{$customer->id}");
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Original Name');

        // View edit form
        $editFormResponse = $this->actingAs($this->admin)->get("/customers/{$customer->id}/edit");
        $editFormResponse->assertStatus(200);
        $editFormResponse->assertSee('Edit Customer Profile');

        // Update customer
        $updateResponse = $this->actingAs($this->admin)->put("/customers/{$customer->id}", [
            'name' => 'Updated Name',
            'email' => 'original@test.com',
            'phone' => '+1-555-9999',
            'company' => 'Acme Updated',
            'status' => 'Active',
            'notes' => 'Updated notes',
        ]);

        $updateResponse->assertRedirect("/customers/{$customer->id}");
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Updated Name',
            'status' => 'Active',
        ]);
    }

    /**
     * 9. Test customer search functionality.
     */
    public function test_customer_search_filters_results(): void
    {
        Customer::create([
            'name' => 'Alpha Software',
            'email' => 'alpha@software.com',
            'company' => 'Alpha Group',
            'status' => 'Active',
            'created_by' => $this->admin->id,
        ]);

        Customer::create([
            'name' => 'Beta Logistics',
            'email' => 'beta@logistics.com',
            'company' => 'Beta Fleet',
            'status' => 'Lead',
            'created_by' => $this->admin->id,
        ]);

        $searchResponse = $this->actingAs($this->admin)->get('/customers?search=Alpha');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Alpha Software');
        $searchResponse->assertDontSee('Beta Logistics');
    }

    /**
     * 10. Test customer deletion and authorization.
     */
    public function test_admin_can_delete_any_customer(): void
    {
        $customer = Customer::create([
            'name' => 'To Delete',
            'email' => 'delete@test.com',
            'status' => 'Inactive',
            'created_by' => $this->employee->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/customers/{$customer->id}");

        $response->assertRedirect('/customers');
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_employee_cannot_delete_other_users_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Admin Customer',
            'email' => 'admin_cust@test.com',
            'status' => 'Active',
            'created_by' => $this->admin->id,
        ]);

        // Employee attempts to delete customer created by Admin
        $response = $this->actingAs($this->employee)->delete("/customers/{$customer->id}");

        $response->assertRedirect('/customers');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    /**
     * 11. REST API — GET /api/customers
     */
    public function test_api_can_list_customers(): void
    {
        Customer::create([
            'name' => 'API Customer',
            'email' => 'api@test.com',
            'status' => 'Active',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonFragment(['name' => 'API Customer']);
    }

    /**
     * 12. REST API — GET /api/customers/{id}
     */
    public function test_api_can_get_single_customer(): void
    {
        $customer = Customer::create([
            'name' => 'API Detail',
            'email' => 'detail@test.com',
            'status' => 'Lead',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'API Detail');

        // Test 404 for non-existent customer
        $notFoundResponse = $this->getJson('/api/customers/99999');
        $notFoundResponse->assertStatus(404);
        $notFoundResponse->assertJsonPath('status', 'error');
    }

    /**
     * 13. REST API — POST /api/customers
     */
    public function test_api_can_create_customer(): void
    {
        $payload = [
            'name' => 'API Created Corp',
            'email' => 'apicreated@corp.com',
            'phone' => '+1-555-8888',
            'company' => 'API Corp',
            'status' => 'Active',
            'notes' => 'Created via REST API',
        ];

        $response = $this->postJson('/api/customers', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('data.name', 'API Created Corp');
        $this->assertDatabaseHas('customers', ['email' => 'apicreated@corp.com']);

        // Test validation failure
        $invalidResponse = $this->postJson('/api/customers', [
            'name' => '', // missing name & email
        ]);
        $invalidResponse->assertStatus(422);
        $invalidResponse->assertJsonPath('status', 'error');
    }

    /**
     * 14. REST API — PUT /api/customers/{id}
     */
    public function test_api_can_update_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Before Update',
            'email' => 'before@update.com',
            'status' => 'Lead',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->putJson("/api/customers/{$customer->id}", [
            'name' => 'After Update',
            'status' => 'Active',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'After Update',
            'status' => 'Active',
        ]);
    }

    /**
     * 15. REST API — DELETE /api/customers/{id}
     */
    public function test_api_can_delete_customer(): void
    {
        $customer = Customer::create([
            'name' => 'To Delete API',
            'email' => 'deleteapi@test.com',
            'status' => 'Inactive',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);

        // Attempting to delete again returns 404
        $deleteAgain = $this->deleteJson("/api/customers/{$customer->id}");
        $deleteAgain->assertStatus(404);
    }
}
