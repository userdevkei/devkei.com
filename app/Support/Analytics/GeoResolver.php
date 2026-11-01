<?php

namespace App\Support\Analytics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeoResolver
{
    public static function lookup(Request $request): array
    {
        $ip = $request->ip();

        if (! $ip || filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return [
                'country' => null,
                'city' => null,
            ];
        }

        try {
            $response = Http::timeout(3)
                ->acceptJson()
                ->get("https://ipwho.is/{$ip}");

            if (! $response->successful()) {
                return [
                    'country' => null,
                    'city' => null,
                ];
            }

            $data = $response->json();

            if (($data['success'] ?? false) !== true) {
                return [
                    'country' => null,
                    'city' => null,
                ];
            }

            return [
                'country' => $data['country_code'] ?? null,
                'city' => $data['city'] ?? null,
            ];
        } catch (\Throwable $e) {
            report($e);

            return [
                'country' => null,
                'city' => null,
            ];
        }
    }
}
