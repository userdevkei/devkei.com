<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Support\Analytics\GeoResolver;
use App\Support\Analytics\TrafficSource;
use App\Support\Analytics\UserAgentParser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * First-party, cookie-based page view tracking.
 * No raw IP addresses are stored.
 *
 * Cookies:
 * - dk_vid (visitor, 1 year)
 * - dk_sid (visit, 30 minutes sliding)
 *
 * The write happens in terminate(), after the response has been sent.
 */
class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldTrack($request)) {
            return $next($request);
        }

        $vid = $this->valid($request->cookie('dk_vid'));
        $sid = $this->valid($request->cookie('dk_sid'));

        $isNew = $vid === null;
        $isEntry = $sid === null;

        $vid ??= Str::random(24);
        $sid ??= Str::random(24);

        $secure = $request->isSecure();

        Cookie::queue(
            'dk_vid',
            $vid,
            60 * 24 * 365,
            '/',
            null,
            $secure,
            true,
            false,
            'lax'
        );

        Cookie::queue(
            'dk_sid',
            $sid,
            30,
            '/',
            null,
            $secure,
            true,
            false,
            'lax'
        );

        /*
         * Store the tracking information on the request so it can
         * be accessed later in terminate().
         */
        $request->attributes->set('dk_track', compact(
            'vid',
            'sid',
            'isNew',
            'isEntry'
        ));

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $track = $request->attributes->get('dk_track');

        if (
            ! $track
            || $response->getStatusCode() !== 200
            || ! str_contains(
                (string) $response->headers->get('Content-Type'),
                'text/html'
            )
        ) {
            return;
        }

        try {
            /*
             * IMPORTANT:
             * $request->ip() should now contain the real visitor IP
             * because Laravel trusts the Cloudflare proxy.
             */
            $ip = $request->ip();

            $ua = UserAgentParser::parse(
                $request->userAgent()
            );

            /*
             * GeoResolver should resolve $request->ip()
             * and return country/city information.
             */
            $geo = GeoResolver::lookup($request);

            $referrer = $request->headers->get('referer');

            $refHost = $referrer
                ? $this->host(
                    parse_url($referrer, PHP_URL_HOST) ?: ''
                )
                : null;

            $ownHost = $this->host(
                $request->getHost()
            );

            $external = $refHost && $refHost !== $ownHost
                ? $refHost
                : null;

            $source = $medium = null;

            if ($track['isEntry']) {
                [$source, $medium] = TrafficSource::classify(
                    $external,
                    [
                        'source' => $request->query('utm_source'),
                        'medium' => $request->query('utm_medium'),
                    ]
                );
            }

            PageView::create([
                'visitor_id' => $track['vid'],
                'session_id' => $track['sid'],

                'is_new' => $track['isNew'],
                'is_entry' => $track['isEntry'],

                'path' => mb_substr(
                    '/' . ltrim($request->path(), '/'),
                    0,
                    255
                ),

                'referrer_host' => $external
                    ? mb_substr($external, 0, 150)
                    : null,

                'referrer_url' => $external
                    ? mb_substr($referrer, 0, 500)
                    : null,

                'source' => $source,
                'medium' => $medium,

                'utm_campaign' => $track['isEntry']
                && $request->query('utm_campaign')
                    ? mb_substr(
                        (string) $request->query('utm_campaign'),
                        0,
                        120
                    )
                    : null,

                /*
                 * Geo information.
                 *
                 * Example:
                 * country = KE
                 * city = Nairobi
                 */
                'country_code' => $geo['country'] ?? null,

                'city' => ! empty($geo['city'])
                    ? mb_substr($geo['city'], 0, 100)
                    : null,

                'device_type' => $ua['device'] ?? null,
                'browser' => $ua['browser'] ?? null,
                'os' => $ua['os'] ?? null,

                'language' => $this->language($request),
            ]);
        } catch (\Throwable $e) {
            /*
             * Analytics must never break the website.
             */
            report($e);
        }
    }

    private function shouldTrack(Request $request): bool
    {
        if (
            ! config('devkei.analytics.enabled')
            || ! $request->isMethod('GET')
            || $request->ajax()
            || $request->headers->get('Purpose') === 'prefetch'
            || $request->headers->get('Sec-Purpose') === 'prefetch'
            || (
                config('devkei.analytics.respect_dnt')
                && $request->headers->get('DNT') === '1'
            )
            || in_array(
                $request->ip(),
                config('devkei.analytics.exclude_ips', []),
                true
            )
            || $request->user()?->is_admin
        ) {
            return false;
        }

        return ! UserAgentParser::parse(
            $request->userAgent()
        )['bot'];
    }

    private function valid(mixed $value): ?string
    {
        return is_string($value)
        && preg_match('/^[A-Za-z0-9]{24}$/', $value)
            ? $value
            : null;
    }

    private function host(string $host): string
    {
        return preg_replace(
            '/^www\./',
            '',
            strtolower($host)
        );
    }

    private function language(Request $request): ?string
    {
        $lang = $request->getPreferredLanguage();

        return $lang
            ? strtolower(explode('_', $lang)[0])
            : null;
    }
}
