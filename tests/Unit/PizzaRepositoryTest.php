<?php

namespace Tests\Unit;

use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PizzaRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function login_for_tests(?string $email = 'email@email.com', ?string $password = 'Hola123!!!'): array
    {
        $user_attributes = [
            'name' => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'email' => $email,
            'password' => bcrypt($password),
        ];

        $this->json('POST', '/api/auth/register', $user_attributes);

        $response = $this->json('POST', 'api/auth/login', compact('email', 'password'));

        $response->assertStatus(200);

        $token = $response->decodeResponseJson()['token'];

        return [
            'Authorization' => 'Bearer ' . $token
        ];
    }


    protected function string_to_lower($data)
    {
        $STR = strval($data);
        $prin = strtolower($STR);
        return $prin;
    }

    public function testStorePizza()
    {
        $headers = $this->login_for_tests();
        $pizza = Pizza::factory()->create();

        $data = $this->string_to_lower($pizza->name);

        $this->json('POST', 'api/pizzas/store', [
            'name' => $data,
            'ingredients' => json_encode($pizza->ingredients),
            'image' => $pizza->image
        ], $headers)->assertStatus(201);

        $this->assertDatabaseHas('pizzas', ['name' => $data, 'ingredients' => json_encode($pizza->ingredients)]);
    }


    public function testStorePizzaWithoutName()
    {
        $headers = $this->login_for_tests();
        $pizza = Pizza::factory()->create();

        $this->json('POST', 'api/pizzas/store', [
            'name' =>  '',
            'ingredients' => json_encode($pizza->ingredients),
            'image' => $pizza->image
        ], $headers)->assertStatus(400);
    }

    public function testShowPizzaById()
    {
        $headers = $this->login_for_tests();

        $pizzas = Pizza::factory()->count(10)->create();
        $firstPizzaId = $pizzas->first()->id;

        $this->json('GET', "api/pizzas/{$firstPizzaId}", [], $headers)
            ->assertStatus(200);

        $this->assertDatabaseHas('pizzas', ['id' => $firstPizzaId]);
    }


    public function testGetallPizzas()
    {
        $headers = $this->login_for_tests();

        Pizza::factory()->count(10)->create();

        $response = $this->json('POST', '/api/pizzas/', [], $headers);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pizzas', ['id' => 1]);
    }


    public function testUpdatePizza()
    {
        $headers = $this->login_for_tests();
        $pizza = Pizza::factory()->create();

        $data = $this->string_to_lower($pizza->name);

        $this->assertDatabaseHas('pizzas', ['name' => $data, 'ingredients' => json_encode($pizza->ingredients)]);

        $newData = $this->string_to_lower($pizza->name);

        $this->json('POST', "api/pizzas/{$pizza->id}", [
            'name' => $newData,
            'ingredients' => json_encode($pizza->ingredients),
            'image' => $pizza->image
        ], $headers)->assertStatus(200);

        $this->assertDatabaseHas('pizzas', ['name' => $newData, 'ingredients' => json_encode($pizza->ingredients)]);
    }

    public function testDeletePizza()
    {
        $headers = $this->login_for_tests();
        $pizza = Pizza::factory()->create();

        $this->assertDatabaseHas('pizzas', ['id' => $pizza->id]);

        $this->json('DELETE', "api/pizzas/{$pizza->id}", [], $headers)->assertStatus(200);

        $this->assertSoftDeleted('pizzas', ['id' => $pizza->id]);
    }
}
