<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ResourceSettings extends Settings
{

    public array $resources;
    public static function group(): string
    {
        return 'resource';
    }
}
