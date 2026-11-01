<?php

namespace App\Services;

use App\Models\DemoRequest;
use App\Models\PageView;
use App\Models\Product;
use App\Models\QuoteRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** All queries target MySQL/MariaDB. Set config/app.php 'timezone' to Africa/Nairobi so hours match local time. */
class AnalyticsService
{
    private const BREAKDOWN_COLUMNS = ['source', 'medium', 'device_type', 'browser', 'os', 'country_code', 'city', 'referrer_host', 'utm_campaign', 'language'];

    private function q(Carbon $from, Carbon $to)
    {
        return PageView::query()->whereBetween('created_at', [$from, $to]);
    }

    public function summary(Carbon $from, Carbon $to): array
    {
        $a = $from->toDateTimeString();
        $b = $to->toDateTimeString();

        $row = $this->q($from, $to)->selectRaw(
            'COUNT(*) AS pageviews, COUNT(DISTINCT visitor_id) AS visitors, COUNT(DISTINCT session_id) AS visits, '
            . 'COUNT(DISTINCT CASE WHEN is_new = 1 THEN visitor_id END) AS new_visitors'
        )->first();

        $single = (int) DB::selectOne(
            'SELECT COUNT(*) AS c FROM (SELECT session_id FROM page_views WHERE created_at BETWEEN ? AND ? GROUP BY session_id HAVING COUNT(*) = 1) t',
            [$a, $b]
        )->c;

        $duration = (float) (DB::selectOne(
            'SELECT AVG(d) AS a FROM (SELECT TIMESTAMPDIFF(SECOND, MIN(created_at), MAX(created_at)) AS d FROM page_views WHERE created_at BETWEEN ? AND ? GROUP BY session_id) t',
            [$a, $b]
        )->a ?? 0);

        $visits = (int) $row->visits;
        $visitors = (int) $row->visitors;
        $new = (int) $row->new_visitors;

        return [
            'visitors' => $visitors,
            'visits' => $visits,
            'pageviews' => (int) $row->pageviews,
            'pages_per_visit' => $visits ? round($row->pageviews / $visits, 2) : 0,
            'bounce_rate' => $visits ? round($single / $visits * 100, 1) : 0,
            'avg_duration' => (int) round($duration),
            'new_visitors' => $new,
            'returning_visitors' => max(0, $visitors - $new),
        ];
    }

    public static function delta(float|int $current, float|int $previous): ?float
    {
        return $previous > 0 ? round(($current - $previous) / $previous * 100, 1) : null;
    }

    public function timeseries(Carbon $from, Carbon $to): array
    {
        $hourly = ($to->timestamp - $from->timestamp) <= 172800;
        $sqlFormat = $hourly ? '%Y-%m-%d %H:00:00' : '%Y-%m-%d';
        $phpFormat = $hourly ? 'Y-m-d H:00:00' : 'Y-m-d';

        $rows = $this->q($from, $to)
            ->selectRaw("DATE_FORMAT(created_at, '{$sqlFormat}') AS bucket, COUNT(*) AS pageviews, COUNT(DISTINCT visitor_id) AS visitors")
            ->groupBy('bucket')
            ->get()
            ->keyBy('bucket');

        $labels = $visitors = $views = [];
        $cursor = $from->copy();

        while ($cursor <= $to) {
            $row = $rows->get($cursor->format($phpFormat));
            $labels[] = $hourly ? $cursor->format('H:i') : $cursor->format('d M');
            $visitors[] = (int) ($row->visitors ?? 0);
            $views[] = (int) ($row->pageviews ?? 0);
            $hourly ? $cursor->addHour() : $cursor->addDay();
        }

        return compact('labels', 'visitors', 'views');
    }

    /** Visits (entry page views) grouped by an attribute. */
    public function breakdown(string $column, Carbon $from, Carbon $to, int $limit = 8): Collection
    {
        abort_unless(in_array($column, self::BREAKDOWN_COLUMNS, true), 500);

        return $this->q($from, $to)
            ->where('is_entry', true)
            ->whereNotNull($column)->where($column, '!=', '')
            ->selectRaw("{$column} AS label, COUNT(*) AS value")
            ->groupBy($column)->orderByDesc('value')->limit($limit)
            ->get()
            ->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->value]);
    }

    public function topPages(Carbon $from, Carbon $to, int $limit = 10): Collection
    {
        return $this->q($from, $to)
            ->selectRaw('path AS label, COUNT(*) AS value, COUNT(DISTINCT visitor_id) AS visitors')
            ->groupBy('path')->orderByDesc('value')->limit($limit)
            ->get()
            ->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->value, 'meta' => $r->visitors . ' visitors']);
    }

    public function entryPages(Carbon $from, Carbon $to, int $limit = 10): Collection
    {
        return $this->q($from, $to)->where('is_entry', true)
            ->selectRaw('path AS label, COUNT(*) AS value')
            ->groupBy('path')->orderByDesc('value')->limit($limit)
            ->get()
            ->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->value]);
    }

    public function productInterest(Carbon $from, Carbon $to): Collection
    {
        $rows = $this->q($from, $to)
            ->where('path', 'like', '/products/%')
            ->selectRaw('path, COUNT(*) AS value')
            ->groupBy('path')->orderByDesc('value')->limit(8)->get();

        $names = Product::whereIn('slug', $rows->map(fn ($r) => substr($r->path, strlen('/products/')))->all())->pluck('name', 'slug');

        return $rows->map(function ($r) use ($names) {
            $slug = substr($r->path, strlen('/products/'));
            return ['label' => $names[$slug] ?? $slug, 'value' => (int) $r->value];
        });
    }

    /** 7 x 24 matrix of visits: rows Monday..Sunday, columns 00..23. */
    public function heatmap(Carbon $from, Carbon $to): array
    {
        $rows = $this->q($from, $to)->where('is_entry', true)
            ->selectRaw('DAYOFWEEK(created_at) AS d, HOUR(created_at) AS h, COUNT(*) AS c')
            ->groupBy('d', 'h')->get();

        $grid = array_fill(0, 7, array_fill(0, 24, 0));

        foreach ($rows as $r) {
            $dayIndex = ((int) $r->d + 5) % 7; // MySQL: 1=Sunday. Shift so 0=Monday.
            $grid[$dayIndex][(int) $r->h] = (int) $r->c;
        }

        return ['grid' => $grid, 'max' => max(1, max(array_map('max', $grid)))];
    }

    public function leads(Carbon $from, Carbon $to): array
    {
        $demos = DemoRequest::whereBetween('created_at', [$from, $to]);
        $quotes = QuoteRequest::whereBetween('created_at', [$from, $to]);

        $bySource = [];
        foreach ([$demos, $quotes] as $query) {
            foreach ((clone $query)->selectRaw("COALESCE(source, 'Direct') AS s, COUNT(*) AS c")->groupBy('s')->get() as $r) {
                $bySource[$r->s] = ($bySource[$r->s] ?? 0) + (int) $r->c;
            }
        }
        arsort($bySource);

        return [
            'demos' => (clone $demos)->count(),
            'quotes' => (clone $quotes)->count(),
            'by_source' => collect($bySource)->map(fn ($v, $k) => ['label' => $k, 'value' => $v])->values()->take(8),
        ];
    }

    public function live(): array
    {
        $recent = PageView::latest('id')->limit(8)->get(['path', 'country_code', 'device_type', 'source', 'is_entry', 'created_at']);

        return [
            'online' => PageView::where('created_at', '>=', now()->subMinutes(5))->distinct()->count('visitor_id'),
            'recent' => $recent->map(fn ($r) => [
                'path' => $r->path,
                'country' => PageView::countryName($r->country_code),
                'device' => $r->device_type,
                'source' => $r->is_entry ? $r->source : null,
                'ago' => $r->created_at->diffForHumans(null, true, true),
            ])->all(),
        ];
    }
}
