<?php

namespace Tests\Feature\Seller;

use App\Models\Seller;
use App\Providers\RouteServiceProvider;
use Hotash\Authable\Registrar;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    public function test_email_verification_screen_can_be_rendered()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $user = Seller::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user, $this->guard)->get('/email/verify');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        Event::fake();

        $user = Seller::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user, $this->guard)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_email_can_not_verified_with_invalid_hash()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $user = Seller::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user, $this->guard)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
