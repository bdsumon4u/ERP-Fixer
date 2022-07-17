<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\URL;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?string $guard = null;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setDomain();
    }

    /**
     * @return void
     */
    protected function setDomain()
    {
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME).'://';
        URL::formatHostUsing(fn () => $scheme.guard_url($this->guard));
    }
}
