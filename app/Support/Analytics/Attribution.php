<?php

namespace App\Support\Analytics;

use App\Models\PageView;
use Illuminate\Http\Request;

class Attribution
{
    /** Links a submitted lead to the visitor's most recent visit (source, campaign, landing page). */
    public static function for(Request $request): array
    {
        $visitorId = $request->cookie('dk_vid');

        if (! is_string($visitorId) || ! preg_match('/^[A-Za-z0-9]{24}$/', $visitorId)) {
            return [];
        }

        $entry = PageView::where('visitor_id', $visitorId)->where('is_entry', true)->latest('id')->first();

        return [
            'visitor_id' => $visitorId,
            'source' => $entry?->source,
            'medium' => $entry?->medium,
            'campaign' => $entry?->utm_campaign,
            'landing_path' => $entry?->path,
            'country_code' => $entry?->country_code,
        ];
    }
}
