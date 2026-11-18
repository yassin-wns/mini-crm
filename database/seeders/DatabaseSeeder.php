<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or retrieve Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@crm.local'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Create or retrieve Employee user
        $employee = User::firstOrCreate(
            ['email' => 'employee@crm.local'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password123'),
                'role' => 'employee',
            ]
        );

        // 3. Create initial sample customers if none exist
        if (Customer::count() === 0) {
            $customers = [
                [
                    'name' => 'Acme Corporation',
                    'email' => 'contact@acme.com',
                    'phone' => '+1-555-0100',
                    'company' => 'Acme Corp',
                    'status' => 'Active',
                    'notes' => 'Enterprise client with 50+ licenses.',
                    'created_by' => $admin->id,
                ],
                [
                    'name' => 'Sarah Connor',
                    'email' => 'sarah@cyberdyne.org',
                    'phone' => '+1-555-0142',
                    'company' => 'Cyberdyne Systems',
                    'status' => 'Lead',
                    'notes' => 'Interested in CRM integration. Meeting scheduled for next Tuesday.',
                    'created_by' => $admin->id,
                ],
                [
                    'name' => 'TechNova Solutions',
                    'email' => 'info@technova.io',
                    'phone' => '+1-555-0188',
                    'company' => 'TechNova Ltd',
                    'status' => 'Active',
                    'notes' => 'Renewed annual support contract.',
                    'created_by' => $employee->id,
                ],
                [
                    'name' => 'Global Logistics Inc',
                    'email' => 'support@globallogistics.com',
                    'phone' => '+1-555-0199',
                    'company' => 'Global Logistics',
                    'status' => 'Inactive',
                    'notes' => 'Account paused due to budget review.',
                    'created_by' => $employee->id,
                ],
                [
                    'name' => 'Green Energy Co',
                    'email' => 'hello@greenenergy.eu',
                    'phone' => '+33-1-40-50-60-70',
                    'company' => 'Green Energy Europe',
                    'status' => 'Lead',
                    'notes' => 'Inquiry received via web form.',
                    'created_by' => $admin->id,
                ],
            ];

            foreach ($customers as $data) {
                Customer::create($data);
            }
        }
    }
}
