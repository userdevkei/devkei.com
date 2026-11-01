<?php

return [
    // Where new demo and quote requests are emailed.
    'notify_email' => env('DEVKEI_NOTIFY_EMAIL', 'kitilionesmus27@gmail.com'),

    'analytics' => [
        'enabled'        => env('ANALYTICS_ENABLED', true),
        // Comma-separated IPs to ignore (your office, home, server monitors).
        'exclude_ips'    => array_filter(array_map('trim', explode(',', (string) env('ANALYTICS_EXCLUDE_IPS', '')))),
        'respect_dnt'    => env('ANALYTICS_RESPECT_DNT', false),
        'retention_days' => env('ANALYTICS_RETENTION_DAYS', 400),
        // Optional MaxMind GeoLite2 City database (see README). Cloudflare's CF-IPCountry header is used first.
        'geoip_path'     => env('GEOIP_DB_PATH', storage_path('app/geoip/GeoLite2-City.mmdb')),
    ],
];
