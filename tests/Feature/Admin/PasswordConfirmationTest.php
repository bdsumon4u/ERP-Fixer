<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = Admin::factory()->create();

        $response = $this->actingAs($user, $this->guard)->get('/user/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = Admin::factory()->create();

        $response = $this->actingAs($user, $this->guard)->post('/user/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = Admin::factory()->create();

        $response = $this->actingAs($user, $this->guard)->post('/user/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
