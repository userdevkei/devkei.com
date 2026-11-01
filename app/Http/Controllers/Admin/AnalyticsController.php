<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Services\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request, AnalyticsService $a)
    {
        [$from, $to, $range] = $this->range($request);

        $seconds = $to->timestamp - $from->timestamp + 1;
        $prevFrom = $from->copy()->subSeconds($seconds);
        $prevTo = $from->copy()->subSecond();

        $s = $a->summary($from, $to);
        $p = $a->summary($prevFrom, $prevTo);
        $leads = $a->leads($from, $to);

        $countries = $a->breakdown('country_code', $from, $to, 10)
            ->map(fn ($r) => ['label' => PageView::countryName($r['label']) . ' (' . $r['label'] . ')', 'value' => $r['value']]);

        return view('admin.analytics.index', [
            'range' => $range, 'from' => $from, 'to' => $to,
            's' => $s,
            'deltas' => collect(['visitors', 'visits', 'pageviews', 'pages_per_visit', 'bounce_rate', 'avg_duration'])
                ->mapWithKeys(fn ($k) => [$k => AnalyticsService::delta($s[$k], $p[$k])])->all(),
            'series' => $a->timeseries($from, $to),
            'sources' => $a->breakdown('source', $from, $to, 8),
            'mediums' => $a->breakdown('medium', $from, $to, 6),
            'devices' => $a->breakdown('device_type', $from, $to, 4),
            'browsers' => $a->breakdown('browser', $from, $to, 6),
            'systems' => $a->breakdown('os', $from, $to, 6),
            'countries' => $countries,
            'cities' => $a->breakdown('city', $from, $to, 8),
            'referrers' => $a->breakdown('referrer_host', $from, $to, 8),
            'campaigns' => $a->breakdown('utm_campaign', $from, $to, 6),
            'languages' => $a->breakdown('language', $from, $to, 5),
            'topPages' => $a->topPages($from, $to),
            'entryPages' => $a->entryPages($from, $to),
            'products' => $a->productInterest($from, $to),
            'heat' => $a->heatmap($from, $to),
            'leads' => $leads,
            'conversion' => $s['visits'] ? round(($leads['demos'] + $leads['quotes']) / $s['visits'] * 100, 2) : 0,
            'liveUrl' => route('admin.analytics.live'),
        ]);
    }

    public function live(AnalyticsService $a)
    {
        return response()->json($a->live());
    }

    public function visitors(Request $request)
    {
        [$from, $to, $range] = $this->range($request);

        return view('admin.analytics.visitors', compact('from', 'to', 'range'));
    }

    /** Server-side DataTables endpoint. */
    public function visitorData(Request $request)
    {
        [$from, $to] = $this->range($request);
        $columns = ['created_at', 'path', 'source', 'country_code', 'device_type', 'browser', 'os', 'is_new'];

        $query = PageView::query()->whereBetween('created_at', [$from, $to]);
        $total = (clone $query)->count();

        if ($search = trim((string) $request->input('search.value', ''))) {
            $query->where(function ($w) use ($search) {
                foreach (['path', 'source', 'country_code', 'city', 'device_type', 'browser', 'os', 'referrer_host'] as $col) {
                    $w->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        $filtered = (clone $query)->count();
        $orderBy = $columns[(int) $request->input('order.0.column', 0)] ?? 'created_at';
        $dir = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';

        $rows = $query->orderBy($orderBy, $dir)->orderByDesc('id')
            ->skip(max(0, (int) $request->input('start', 0)))
            ->take(min(100, max(1, (int) $request->input('length', 25))))
            ->get();

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $rows->map(fn ($r) => [
                e($r->created_at->format('d M Y, H:i')),
                '<code>' . e($r->path) . '</code>',
                $r->is_entry
                    ? e($r->source ?: 'Direct') . '<small>' . e($r->medium) . '</small>'
                    : '<span class="muted">Internal</span>',
                e(trim(($r->country_code ? PageView::countryName($r->country_code) : 'Unknown') . ($r->city ? ', ' . $r->city : ''))),
                e(ucfirst((string) $r->device_type)),
                e($r->browser),
                e($r->os),
                $r->is_new ? '<span class="badge badge-blue">New</span>' : '<span class="badge badge-gray">Returning</span>',
            ])->all(),
        ]);
    }

    /** @return array{0:Carbon,1:Carbon,2:string} */
    private function range(Request $request): array
    {
        $range = $request->query('range', '30d');
        $to = now()->endOfDay();

        $from = match ($range) {
            'today' => now()->startOfDay(),
            '7d' => now()->subDays(6)->startOfDay(),
            '90d' => now()->subDays(89)->startOfDay(),
            'custom' => $this->customStart($request, $to),
            default => now()->subDays(29)->startOfDay(),
        };

        if ($range === 'custom') {
            $to = $this->customEnd($request) ?? $to;
        } elseif (! in_array($range, ['today', '7d', '90d'], true)) {
            $range = '30d';
        }

        if ($from > $to) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        if ($from->diffInDays($to) > 366) {
            $from = $to->copy()->subDays(366)->startOfDay();
        }

        return [$from, $to, $range];
    }

    private function customStart(Request $request, Carbon $fallbackTo): Carbon
    {
        try {
            return Carbon::parse($request->query('from'))->startOfDay();
        } catch (\Throwable) {
            return $fallbackTo->copy()->subDays(29)->startOfDay();
        }
    }

    private function customEnd(Request $request): ?Carbon
    {
        try {
            return $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : null;
        } catch (\Throwable) {
            return null;
        }
    }
}
