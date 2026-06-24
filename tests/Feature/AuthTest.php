<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ─── Register ─────────────────────────────────────────────────────────────

    public function test_register_page_loads(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register.attempt'), [
            'name'                  => 'Ali Ahmadi',
            'email'                 => 'ali@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'ali@test.com']);
        $this->assertAuthenticated();
    }

    public function test_register_requires_name_email_password(): void
    {
        $this->post(route('register.attempt'), [])
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'duplicate@test.com']);

        $this->post(route('register.attempt'), [
            'name'                  => 'Ali',
            'email'                 => 'duplicate@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_register_rejects_password_mismatch(): void
    {
        $this->post(route('register.attempt'), [
            'name'                  => 'Ali',
            'email'                 => 'ali@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'wrong',
        ])->assertSessionHasErrors('password');
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    public function test_login_page_loads(): void
    {
        $this->get(route('login'))->assertOk();
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $this->post(route('login.attempt'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->post(route('login.attempt'), [
            'email'    => $user->email,
            'password' => 'wrong',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $this->post(route('login.attempt'), [
            'email'    => 'nobody@test.com',
            'password' => 'password',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    // ─── Guest redirects ──────────────────────────────────────────────────────

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }
}
