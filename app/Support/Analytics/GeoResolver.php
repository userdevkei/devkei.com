<?php

namespace App\Support\Analytics;

use Illuminate\Http\Request;

class GeoResolver
{
    private static $reader = null;

    /** @return array{country:?string, city:?string} */
    public static function lookup(Request $request): array
    {
        // 1. Cloudflare (or another proxy) already resolved the country.
        $cf = strtoupper((string) $request->header('CF-IPCountry'));
        $country = preg_match('/^[A-Z]{2}$/', $cf) && ! in_array($cf, ['XX', 'T1'], true) ? $cf : null;
        $city = null;

        // 2. Optional MaxMind GeoLite2 database (composer require geoip2/geoip2).
        $path = config('devkei.analytics.geoip_path');

        if (class_exists(\GeoIp2\Database\Reader::class) && $path && is_file($path)) {
            try {
                self::$reader ??= new \GeoIp2\Database\Reader($path);
                $record = self::$reader->city($request->ip());
                $country ??= $record->country->isoCode;
                $city = $record->city->name;
            } catch (\Throwable) {
                // Private or unknown address: leave as is.
            }
        }

        return ['country' => $country, 'city' => $city];
    }
}
