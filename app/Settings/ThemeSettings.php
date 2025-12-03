<?php

namespace App\Settings;

use Illuminate\Support\Str;
use Filament\Support\Colors\Color;
use Spatie\LaravelSettings\Settings;
use Illuminate\Support\Facades\Cache;

class ThemeSettings extends Settings
{
    public string $primary_color;

    public string $danger_color;

    public string $gray_color;

    public string $info_color;

    public string $success_color;

    public string $warning_color;

    public string $background_type;

    public bool $use_custom_theme;

    public static function group(): string
    {
        return 'theme';
    }

    // public static function cacheKeys()
    // {
    //     return [
    //         'panelColors' => 'panel:colors',
    //     ];
    // }

    public static function colorsMap(): mixed
    {
        // return Cache::rememberForever('colors:map', function () {
            return [
                'Slate' => Color::Slate,
                'Gray' => Color::Gray,
                'Zinc' => Color::Zinc,
                'Neutral' => Color::Neutral,
                'Stone' => Color::Stone,
                'Red' => Color::Red,
                'Orange' => Color::Orange,
                'Amber' => Color::Amber,
                'Yellow' => Color::Yellow,
                'Lime' => Color::Lime,
                'Green' => Color::Green,
                'Emerald' => Color::Emerald,
                'Teal' => Color::Teal,
                'Cyan' => Color::Cyan,
                'Sky' => Color::Sky,
                'Blue' => Color::Blue,
                'Indigo' => Color::Indigo,
                'Violet' => Color::Violet,
                'Purple' => Color::Purple,
                'Fuchsia' => Color::Fuchsia,
                'Pink' => Color::Pink,
                'Rose' => Color::Rose,
            ];
        // });
    }

    public static function getColorKeys(): array
    {
        return array_keys(self::colorsMap());
    }

    public static function getColorKeysRule(): string
    {
        return implode(',', self::getColorKeys());
    }

    public function getColors(): array
    {
        $colorsMap = self::colorsMap();
        // $cacheKey = self::cacheKeys()['panelColors'];

        // return Cache::rememberForever($cacheKey, function () use ($colorsMap) {
            return [
                'danger' => $colorsMap[$this->danger_color] ?? Color::Red,
                'gray' => $colorsMap[$this->gray_color] ?? Color::Slate,
                'info' => $colorsMap[$this->info_color] ?? Color::Blue,
                'primary' => $colorsMap[$this->primary_color] ?? Color::Orange,
                'success' => $colorsMap[$this->success_color] ?? Color::Emerald,
                'warning' => $colorsMap[$this->warning_color] ?? Color::Amber,

            ];
        // });
    }


    public function getBackgroundPatternImage(): string
    {
        $colorName = Str::lower($this->primary_color);
        return asset("storage/images/backgrounds/$colorName.png");
    }
}
