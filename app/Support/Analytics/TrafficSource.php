<?php

namespace App\Support\Analytics;

class TrafficSource
{
    /** Order matters: more specific patterns first. Each maps to [display name, medium]. */
    private const MAP = [
        '/(^|\.)mail\.google\.com$/'                       => ['Gmail', 'email'],
        '/(^|\.)(outlook\.live|outlook\.office|outlook\.office365)\.com$/' => ['Outlook', 'email'],
        '/(^|\.)mail\.yahoo\.com$/'                        => ['Yahoo Mail', 'email'],
        '/(^|\.)google\.|googlequicksearchbox/'            => ['Google', 'organic'],
        '/(^|\.)bing\.com$/'                               => ['Bing', 'organic'],
        '/(^|\.)duckduckgo\.com$/'                         => ['DuckDuckGo', 'organic'],
        '/(^|\.)yahoo\.com$/'                              => ['Yahoo', 'organic'],
        '/(^|\.)(yandex\.[a-z.]+|ecosia\.org|baidu\.com)$/' => ['Other search', 'organic'],
        '/(^|\.)(facebook\.com|fb\.com|fb\.me|fbcdn\.net)$/' => ['Facebook', 'social'],
        '/(^|\.)instagram\.com$/'                          => ['Instagram', 'social'],
        '/(^|\.)(twitter\.com|x\.com|t\.co)$/'             => ['X (Twitter)', 'social'],
        '/(^|\.)(linkedin\.com|lnkd\.in)$/'                => ['LinkedIn', 'social'],
        '/(^|\.)(youtube\.com|youtu\.be)$/'                => ['YouTube', 'social'],
        '/(^|\.)tiktok\.com$/'                             => ['TikTok', 'social'],
        '/(^|\.)(whatsapp\.com|wa\.me)$/'                  => ['WhatsApp', 'social'],
        '/(^|\.)(telegram\.org|t\.me)$/'                   => ['Telegram', 'social'],
        '/(^|\.)pinterest\.[a-z.]+$/'                      => ['Pinterest', 'social'],
        '/(^|\.)reddit\.com$/'                             => ['Reddit', 'social'],
    ];

    /** @return array{0:string,1:string} [source, medium] */
    public static function classify(?string $referrerHost, array $utm = []): array
    {
        $utmSource = trim((string) ($utm['source'] ?? ''));

        if ($utmSource !== '') {
            $medium = strtolower(trim((string) ($utm['medium'] ?? ''))) ?: 'campaign';
            return [mb_substr(ucfirst($utmSource), 0, 100), mb_substr($medium, 0, 40)];
        }

        if (! $referrerHost) {
            return ['Direct', 'direct'];
        }

        foreach (self::MAP as $pattern => [$name, $medium]) {
            if (preg_match($pattern, $referrerHost)) {
                return [$name, $medium];
            }
        }

        return [mb_substr($referrerHost, 0, 100), 'referral'];
    }
}
