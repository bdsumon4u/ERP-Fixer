<?php

namespace Tests\Feature\Admin;

use App\Providers\RouteServiceProvider;
use Hotash\Authable\Registrar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    public function test_registration_screen_can_be_rendered()
    {
        if (! in_array(Features::registration(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled()
    {
        if (! in_array(Features::registration(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_admins_can_register()
    {
        if (! in_array(Features::registration(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated($this->guard);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
