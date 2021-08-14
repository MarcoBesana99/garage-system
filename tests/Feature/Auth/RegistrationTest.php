<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  public function test_it_allows_to_create_new_users()
  {
    $this->postJson('api/auth/register', [
      'name' => $this->faker->name(),
      'email' => $email = $this->faker->safeEmail(),
      'password' => $password = Str::random(10),
      'password_confirmation' => $password
    ])->assertStatus(201)
      ->assertJsonStructure([
        'access_token',
        'token_type'
      ]);

    $this
      ->assertDatabaseHas('users', ['email' => $email])
      ->assertDatabaseCount('users', 1);
  }

  public function test_it_does_not_allow_duplicate_emails()
  {
    $email = 'test_email@gmail.com';
    User::factory()->create(['email' => $email]);

    $this->postJson('api/auth/register', [
      'name' => $this->faker->name(),
      'email' => $email,
      'password' => $password = Str::random(10),
      'password_confirmation' => $password
    ])->assertJsonFragment([
      'email' => ['The email has already been taken.'],
      'message' => 'The given data was invalid.'
    ]);

    $this
      ->assertDatabaseHas('users', ['email' => $email])
      ->assertDatabaseCount('users', 1);
  }
}
