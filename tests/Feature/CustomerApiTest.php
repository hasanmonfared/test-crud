<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_customer()
    {
        $customerData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '123456789',
            'email' => 'john@example.com',
            'bank_account_number' => '1234567890'
        ];

        $response = $this->json('POST', '/api/customers', $customerData);

        $response->assertStatus(201); // Assert that the response is a successful creation
        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
    }

}
