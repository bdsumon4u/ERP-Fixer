<?php

if (! function_exists('system_path')) {
    function system_path($path = null): string
    {
        return base_path('system/'.$path);
    }
}
