<?php

namespace App\Providers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Response;
use Inertia\ResponseFactory;

class InertiaThemeProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend(ResponseFactory::class, fn () => new InertiaFactory());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [InertiaFactory::class];
    }
}

class InertiaFactory extends ResponseFactory
{
    /**
     * @param  string  $component
     * @param  array|Arrayable  $props
     * @return Response
     */
    public function render(string $component, $props = []): Response
    {
        $namespace = 'themes/default::';

        if (! Str::startsWith($component, '>')) { // Not Called From System
            if (function_exists('tenant') && ($theme = tenant('theme'))) {
                $themePath = system_path('themes/'.$theme.'/resources/js/Pages/');
                if (file_exists($themePath.$component.'.vue')) {
                    $namespace = 'themes/'.$theme.'::';
                }
            }

            return parent::render($namespace.$component, $props);
        }

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $after = Str::after(Arr::get($stack[1], 'file', ''), base_path('system/'));
        $namespace = implode('/', Arr::only(explode('/', $after), [0, 1]));

        return parent::render(Str::replaceFirst('>', $namespace.'::', $component), $props);
    }
}
