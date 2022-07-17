<?php

namespace Tests\Feature\Seller;

use App\Models\Seller;
use Hotash\Authable\Registrar;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    public function test_reset_password_link_screen_can_be_rendered()
    {
        if (! in_array(Features::resetPasswords(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Password updates are not enabled.');
        }

        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        if (! in_array(Features::resetPasswords(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Password updates are not enabled.');
        }

        Notification::fake();

        $user = Seller::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        if (! in_array(Features::resetPasswords(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Password updates are not enabled.');
        }

        Notification::fake();

        $user = Seller::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        if (! in_array(Features::resetPasswords(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Password updates are not enabled.');
        }

        Notification::fake();

        $user = Seller::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
