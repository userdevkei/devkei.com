<?php

namespace App\Support\Analytics;

class UserAgentParser
{
    private const BOT = '/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|embedly|preview|headless|lighthouse|pingdom|uptime|monitor|curl\/|wget|python-requests|python-urllib|java\/|go-http|libwww|okhttp\/|httpclient|scrapy|axios|node-fetch|postman|whatsapp\/|telegrambot|discord|semrush|ahrefs|mj12|dotbot|petalbot|yandex(?!browser)/i';

    public static function parse(?string $ua): array
    {
        $ua = (string) $ua;

        if ($ua === '' || preg_match(self::BOT, $ua)) {
            return ['bot' => true, 'device' => null, 'browser' => null, 'os' => null];
        }

        return [
            'bot' => false,
            'device' => self::device($ua),
            'browser' => self::browser($ua),
            'os' => self::os($ua),
        ];
    }

    private static function device(string $ua): string
    {
        if (preg_match('/ipad|tablet|kindle|silk|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }

        if (preg_match('/mobi|iphone|ipod|android|windows phone|opera mini|blackberry/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private static function browser(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/FBAN|FBAV|FB_IAB/i', $ua) => 'Facebook app',
            (bool) preg_match('/Instagram/i', $ua) => 'Instagram app',
            (bool) preg_match('/TikTok|musical_ly|BytedanceWebview/i', $ua) => 'TikTok app',
            (bool) preg_match('/Opera Mini|OPiOS/i', $ua) => 'Opera Mini',
            (bool) preg_match('/Edg(e|A|iOS)?\//i', $ua) => 'Edge',
            (bool) preg_match('/OPR\/|Opera/i', $ua) => 'Opera',
            (bool) preg_match('/SamsungBrowser/i', $ua) => 'Samsung Internet',
            (bool) preg_match('/UCBrowser|UCWEB/i', $ua) => 'UC Browser',
            (bool) preg_match('/Firefox|FxiOS/i', $ua) => 'Firefox',
            (bool) preg_match('/Chrome|CriOS/i', $ua) => 'Chrome',
            (bool) preg_match('/Safari/i', $ua) => 'Safari',
            (bool) preg_match('/MSIE|Trident/i', $ua) => 'Internet Explorer',
            default => 'Other',
        };
    }

    private static function os(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/Windows Phone/i', $ua) => 'Windows Phone',
            (bool) preg_match('/Windows/i', $ua) => 'Windows',
            (bool) preg_match('/Android/i', $ua) => 'Android',
            (bool) preg_match('/iPhone|iPad|iPod/i', $ua) => 'iOS',
            (bool) preg_match('/Mac OS X|Macintosh/i', $ua) => 'macOS',
            (bool) preg_match('/CrOS/i', $ua) => 'ChromeOS',
            (bool) preg_match('/Linux|X11/i', $ua) => 'Linux',
            default => 'Other',
        };
    }
}
