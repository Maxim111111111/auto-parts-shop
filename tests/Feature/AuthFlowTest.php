<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_and_reach_profile(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Иван Петров',
            'email' => 'ivan@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'ivan@example.com']);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::query()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $loginResponse = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $loginResponse->assertRedirect(route('storefront.index'));
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->post(route('logout'));

        $logoutResponse->assertRedirect(route('storefront.index'));
        $this->assertGuest();
    }

    public function test_profile_routes_require_authentication(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_profile_and_password(): void
    {
        $user = User::query()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => 'secret123',
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'New Name',
                'email' => 'new@example.com',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $this->actingAs($user)
            ->put(route('profile.password.update'), [
                'current_password' => 'secret123',
                'password' => 'newSecret123',
                'password_confirmation' => 'newSecret123',
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertTrue(Hash::check('newSecret123', $user->password));
    }
}
