<?php

namespace Tests\Feature\Seller;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    public function test_other_browser_sessions_can_be_logged_out()
    {
        $this->actingAs($user = Seller::factory()->create(), $this->guard);

        $response = $this->delete('/user/other-browser-sessions', [
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
    }
}
