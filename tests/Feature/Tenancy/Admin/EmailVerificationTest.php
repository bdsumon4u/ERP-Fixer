<?php

namespace Tests\Feature\Tenancy\Admin;

use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Hotash\Authable\Registrar;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    protected bool $tenancy = true;

    public function test_email_verification_screen_can_be_rendered()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $admin = Admin::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($admin, $this->guard)->get('/email/verify');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        Event::fake();

        $admin = Admin::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $admin->id, 'hash' => sha1($admin->email)]
        );

        $response = $this->actingAs($admin, $this->guard)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($admin->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_email_can_not_verified_with_invalid_hash()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $admin = Admin::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $admin->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($admin, $this->guard)->get($verificationUrl);

        $this->assertFalse($admin->fresh()->hasVerifiedEmail());
    }
}
