<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use App\Models\PageView;
use App\Models\Product;
use App\Models\QuoteRequest;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __invoke(AnalyticsService $a)
    {
        $to = now()->endOfDay();
        $from = now()->subDays(6)->startOfDay();
        $prevFrom = $from->copy()->subDays(7);
        $prevTo = $from->copy()->subSecond();

        $s = $a->summary($from, $to);
        $p = $a->summary($prevFrom, $prevTo);

        $recent = DemoRequest::with('product')->latest()->limit(6)->get()->map(fn ($l) => $this->row($l, 'demos'))
            ->concat(QuoteRequest::with('product')->latest()->limit(6)->get()->map(fn ($l) => $this->row($l, 'quotes')))
            ->sortByDesc('created_at')->take(7)->values();

        return view('admin.dashboard', [
            's' => $s,
            'deltas' => [
                'visitors' => AnalyticsService::delta($s['visitors'], $p['visitors']),
                'pageviews' => AnalyticsService::delta($s['pageviews'], $p['pageviews']),
            ],
            'series' => $a->timeseries($from, $to),
            'newDemos' => DemoRequest::where('status', 'new')->count(),
            'newQuotes' => QuoteRequest::where('status', 'new')->count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'sources' => $a->breakdown('source', $from, $to, 5),
            'topPages' => $a->topPages($from, $to, 5),
            'recent' => $recent,
            'liveUrl' => route('admin.analytics.live'),
        ]);
    }

    private function row($lead, string $type): array
    {
        return [
            'type' => $type,
            'id' => $lead->id,
            'name' => $lead->name,
            'organization' => $lead->organization,
            'product' => $lead->product?->name,
            'status' => $lead->status,
            'created_at' => $lead->created_at,
        ];
    }
}
