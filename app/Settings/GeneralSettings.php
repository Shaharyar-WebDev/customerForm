<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public ?string $site_logo;

    public string $navigation_type;

    public bool $spa_mode;

    public bool $website_spa_mode;

    public bool $spa_mode_prefetching;

    public int $currency_id;

    public string $content_width;

    public ?string $api_url;

    public ?string $api_token;

    public ?string $message_template;
    // public ?string $api_channel;

    public static function group(): string
    {
        return 'general';
    }
}
