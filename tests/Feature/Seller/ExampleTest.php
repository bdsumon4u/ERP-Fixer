<?php

namespace Tests\Feature\Seller;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected ?string $guard = 'seller';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
