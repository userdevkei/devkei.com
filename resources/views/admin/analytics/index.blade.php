@extends('admin.layout')
@section('title', 'Traffic analytics')
@section('heading', 'Traffic analytics')
@section('subtitle', $from->format('d M Y') . ' to ' . $to->format('d M Y'))
@section('actions')
    @include('admin.analytics._range', ['action' => route('admin.analytics.index')])
@endsection

@php
    $dur = $s['avg_duration'] >= 60 ? floor($s['avg_duration'] / 60) . 'm ' . ($s['avg_duration'] % 60) . 's' : $s['avg_duration'] . 's';
    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $doughnut = fn ($rows, $fmt = null) => json_encode([
        'type' => 'doughnut',
        'labels' => $rows->map(fn ($r) => $fmt ? $fmt($r['label']) : $r['label'])->all(),
        'datasets' => [['data' => $rows->pluck('value')->all()]],
    ]);
@endphp

@section('content')
<div class="kpi-grid kpi-6">
    @include('admin._kpi', ['label' => 'Visitors', 'value' => number_format($s['visitors']), 'icon' => 'users', 'delta' => $deltas['visitors']])
    @include('admin._kpi', ['label' => 'Visits', 'value' => number_format($s['visits']), 'icon' => 'globe', 'delta' => $deltas['visits']])
    @include('admin._kpi', ['label' => 'Page views', 'value' => number_format($s['pageviews']), 'icon' => 'eye', 'delta' => $deltas['pageviews']])
    @include('admin._kpi', ['label' => 'Pages per visit', 'value' => $s['pages_per_visit'], 'icon' => 'layers', 'delta' => $deltas['pages_per_visit']])
    @include('admin._kpi', ['label' => 'Bounce rate', 'value' => $s['bounce_rate'] . '%', 'icon' => 'target', 'delta' => $deltas['bounce_rate'], 'lowerBetter' => true])
    @include('admin._kpi', ['label' => 'Avg. visit time', 'value' => $dur, 'icon' => 'clock', 'delta' => $deltas['avg_duration']])
</div>

<div class="grid-main">
    <section class="card">
        <header class="card-head"><h2>Visitors and page views</h2></header>
        <div class="chart-box"><canvas data-chart data-config="{{ json_encode(['type' => 'line', 'labels' => $series['labels'], 'datasets' => [
            ['label' => 'Visitors', 'data' => $series['visitors']],
            ['label' => 'Page views', 'data' => $series['views']],
        ]]) }}"></canvas></div>
    </section>
    <section class="card">
        <header class="card-head"><h2>Conversion</h2></header>
        <div class="conv">
            <div><b>{{ $conversion }}%</b><span>of visits became a request</span></div>
            <div><b>{{ $leads['demos'] }}</b><span>demo requests</span></div>
            <div><b>{{ $leads['quotes'] }}</b><span>quote requests</span></div>
        </div>
        <h3 class="card-sub">New vs returning visitors</h3>
        <div class="chart-box chart-sm"><canvas data-chart data-config="{{ json_encode(['type' => 'doughnut', 'labels' => ['New', 'Returning'], 'datasets' => [['data' => [$s['new_visitors'], $s['returning_visitors']]]]]) }}"></canvas></div>
    </section>
</div>

<div class="grid-3">
    <section class="card">
        <header class="card-head"><h2>Traffic sources</h2></header>
        @include('admin.analytics._bars', ['rows' => $sources, 'total' => max(1, $s['visits'])])
    </section>
    <section class="card">
        <header class="card-head"><h2>Channels</h2></header>
        <div class="chart-box chart-sm"><canvas data-chart data-config="{{ $doughnut($mediums, fn ($l) => ucfirst($l)) }}"></canvas></div>
    </section>
    <section class="card">
        <header class="card-head"><h2>Devices</h2></header>
        <div class="chart-box chart-sm"><canvas data-chart data-config="{{ $doughnut($devices, fn ($l) => ucfirst($l)) }}"></canvas></div>
    </section>
</div>

<div class="grid-3">
    <section class="card"><header class="card-head"><h2>Browsers</h2></header>@include('admin.analytics._bars', ['rows' => $browsers, 'total' => max(1, $s['visits'])])</section>
    <section class="card"><header class="card-head"><h2>Operating systems</h2></header>@include('admin.analytics._bars', ['rows' => $systems, 'total' => max(1, $s['visits'])])</section>
    <section class="card"><header class="card-head"><h2>Languages</h2></header>@include('admin.analytics._bars', ['rows' => $languages->map(fn ($r) => ['label' => strtoupper($r['label']), 'value' => $r['value']]), 'total' => max(1, $s['visits'])])</section>
</div>

<div class="grid-2">
    <section class="card">
        <header class="card-head"><h2>Countries</h2></header>
        @include('admin.analytics._bars', ['rows' => $countries, 'total' => max(1, $s['visits']), 'empty' => 'No location data yet. See the README to enable country detection.'])
    </section>
    <section class="card">
        <header class="card-head"><h2>Cities</h2></header>
        @include('admin.analytics._bars', ['rows' => $cities, 'total' => max(1, $s['visits']), 'empty' => 'City data needs the optional GeoLite2 database (see README).'])
    </section>
</div>

<div class="grid-2">
    <section class="card"><header class="card-head"><h2>Top pages</h2></header>@include('admin.analytics._bars', ['rows' => $topPages, 'total' => max(1, $s['pageviews'])])</section>
    <section class="card"><header class="card-head"><h2>Entry pages</h2><span class="muted">Where visits start</span></header>@include('admin.analytics._bars', ['rows' => $entryPages, 'total' => max(1, $s['visits'])])</section>
</div>

<section class="card">
    <header class="card-head"><h2>Busiest times</h2><span class="muted">Visits by day and hour, local time</span></header>
    <div class="heat-scroll">
        <div class="heat">
            <div class="heat-row heat-head"><span></span>@for($h = 0; $h < 24; $h++)<span>{{ $h % 3 === 0 ? sprintf('%02d', $h) : '' }}</span>@endfor</div>
            @foreach($days as $i => $day)
                <div class="heat-row"><span class="heat-day">{{ $day }}</span>
                    @for($h = 0; $h < 24; $h++)
                        @php $v = $heat['grid'][$i][$h]; @endphp
                        <i class="heat-cell" style="--i:{{ round($v / $heat['max'], 2) }}" title="{{ $day }} {{ sprintf('%02d', $h) }}:00, {{ $v }} {{ $v === 1 ? 'visit' : 'visits' }}"></i>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="grid-3">
    <section class="card"><header class="card-head"><h2>Referring sites</h2></header>@include('admin.analytics._bars', ['rows' => $referrers, 'total' => max(1, $s['visits']), 'empty' => 'No referring sites yet.'])</section>
    <section class="card"><header class="card-head"><h2>Campaigns</h2><span class="muted">utm_campaign</span></header>@include('admin.analytics._bars', ['rows' => $campaigns, 'total' => max(1, $s['visits']), 'empty' => 'Tag links with ?utm_source=&utm_campaign= to see campaigns here.'])</section>
    <section class="card"><header class="card-head"><h2>Product interest</h2></header>@include('admin.analytics._bars', ['rows' => $products, 'total' => max(1, $products->sum('value')), 'empty' => 'No product page views yet.'])</section>
</div>

<section class="card">
    <header class="card-head"><h2>Which sources bring requests</h2><span class="muted">Demo and quote requests by traffic source</span></header>
    @include('admin.analytics._bars', ['rows' => $leads['by_source'], 'total' => max(1, $leads['demos'] + $leads['quotes']), 'empty' => 'No requests in this period.'])
</section>
@endsection

@push('vendor')<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>@endpush
@push('scripts')<script src="{{ asset('js/admin-charts.js') }}?v=1"></script>@endpush
