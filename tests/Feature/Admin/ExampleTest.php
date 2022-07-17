<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected ?string $guard = 'admin';

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
