<?php

namespace Tests\Feature;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_all_customers()
    {
        $customers = Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);

        $response->assertJson(CustomerResource::collection($customers)->response()->getData(true));
    }

    public function test_store_method_creates_customer_with_valid_input()
    {
        $email = $this->faker->unique()->safeEmail;
        $response = $this->postJson('/api/customers', [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $email,
            'bank_account_number' => '1234567890'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('customers', ['email' => $email]);
    }

    public function test_store_method_validates_input()
    {
        $response = $this->postJson('/api/customers', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['firstname', 'lastname', 'date_of_birth', 'phone_number', 'email']);
    }

    public function test_can_show_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                'id' => $customer->id,
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'date_of_birth' => $customer->date_of_birth,
                'phone_number' => $customer->phone_number,
                'email' => $customer->email,
                'bank_account_number' => $customer->bank_account_number,
            ]
        ]);
    }

    public function test_show_method_returns_404_for_nonexistent_customer()
    {
        $response = $this->getJson('/api/customers/999');

        $response->assertStatus(404);
    }

    public function test_can_update_customer()
    {
        $customer = Customer::factory()->create();
        $email = $this->faker->unique()->safeEmail;
        $firstname = $this->faker->firstName();
        $lastname = $this->faker->lastName();
        $date_of_birth = $this->faker->date();
        $response = $this->putJson("/api/customers/{$customer->id}", [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'date_of_birth' => $date_of_birth,
            'phone_number' => '09377561162',
            'email' => $email,
            'bank_account_number' => '544556564',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'date_of_birth' => $date_of_birth,
            'phone_number' => '09377561162',
            'email' => $email,
            'bank_account_number' => '544556564',
        ]);
    }

    public function test_update_method_validates_input()
    {
        $customer = Customer::factory()->create();

        $response = $this->putJson("/api/customers/{$customer->id}", []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['firstname', 'lastname', 'date_of_birth', 'phone_number', 'email']);
    }

    public function test_update_method_returns_404_for_nonexistent_customer()
    {
        $response = $this->putJson('/api/customers/999', [
            'firstname' => 'test',
            'lastname' => 'test',
        ]);

        $response->assertStatus(404);
    }

    public function test_can_delete_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_destroy_method_returns_404_for_nonexistent_customer()
    {
        $response = $this->deleteJson('/api/customers/999');

        $response->assertStatus(404);
    }
}
