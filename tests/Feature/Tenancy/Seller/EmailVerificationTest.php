<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
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

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_email_verification_screen_can_be_rendered()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $seller = Seller::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($seller, $this->guard)->get('/email/verify');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        Event::fake();

        $seller = Seller::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $seller->id, 'hash' => sha1($seller->email)]
        );

        $response = $this->actingAs($seller, $this->guard)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($seller->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_email_can_not_verified_with_invalid_hash()
    {
        if (! in_array(Features::emailVerification(), Registrar::all()[$this->guard]['features'])) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $seller = Seller::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $seller->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($seller, $this->guard)->get($verificationUrl);

        $this->assertFalse($seller->fresh()->hasVerifiedEmail());
    }
}
