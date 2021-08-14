<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  public function test_it_allows_to_login_user()
  {

    $user = User::factory()->create([
      'name' => $this->faker->name(),
      'email' => $email = $this->faker()->safeEmail(),
      'password' => bcrypt($password = Str::random(10)),
    ]);

    $response = $this->postJson('api/auth/login', [
      'email' => $email,
      'password' => $password,
    ])->assertStatus(200)
      ->assertJsonStructure([
        'access_token',
        'token_type'
      ]);
  }
}
