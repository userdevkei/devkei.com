<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'is_new' => 'boolean',
        'is_entry' => 'boolean',
        'created_at' => 'datetime',
    ];

    public static function countryName(?string $code): string
    {
        if (! $code) {
            return 'Unknown';
        }

        $code = strtoupper($code);

        if (class_exists(\Locale::class)) {
            $name = \Locale::getDisplayRegion('-' . $code, 'en');
            if ($name && $name !== $code && $name !== '-' . $code) {
                return $name;
            }
        }

        return $code;
    }
}
