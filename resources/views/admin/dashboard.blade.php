@extends('admin.layout')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subtitle', 'Website traffic over the last 7 days and the leads waiting for you.')
@section('actions')
    <a class="btn btn-outline btn-sm" href="{{ route('admin.analytics.index') }}"><x-admin.icon name="chart" :size="16"/> Full analytics</a>
@endsection

@section('content')
<div class="kpi-grid">
    @include('admin._kpi', ['label' => 'Visitors', 'value' => number_format($s['visitors']), 'icon' => 'users', 'delta' => $deltas['visitors']])
    @include('admin._kpi', ['label' => 'Page views', 'value' => number_format($s['pageviews']), 'icon' => 'eye', 'delta' => $deltas['pageviews']])
    <a class="kpi-link" href="{{ route('admin.leads.index', 'demos') }}">@include('admin._kpi', ['label' => 'New demo requests', 'value' => $newDemos, 'icon' => 'calendar', 'hint' => 'Waiting for a reply'])</a>
    <a class="kpi-link" href="{{ route('admin.leads.index', 'quotes') }}">@include('admin._kpi', ['label' => 'New quote requests', 'value' => $newQuotes, 'icon' => 'file', 'hint' => 'Waiting for a quote'])</a>
</div>

<div class="grid-main">
    <section class="card">
        <header class="card-head"><h2>Traffic, last 7 days</h2></header>
        <div class="chart-box"><canvas data-chart data-config="{{ json_encode(['type' => 'line', 'labels' => $series['labels'], 'datasets' => [
            ['label' => 'Visitors', 'data' => $series['visitors']],
            ['label' => 'Page views', 'data' => $series['views']],
        ]]) }}"></canvas></div>
    </section>
    <section class="card">
        <header class="card-head"><h2>Where visitors come from</h2></header>
        @include('admin.analytics._bars', ['rows' => $sources, 'total' => max(1, $s['visits'])])
    </section>
</div>

<div class="grid-main">
    <section class="card">
        <header class="card-head"><h2>Latest requests</h2></header>
        @if($recent->isEmpty())
            <p class="empty">No requests yet. They appear here as soon as someone fills in a form.</p>
        @else
        <div class="table-scroll">
            <table class="simple-table">
                <thead><tr><th>Received</th><th>Contact</th><th>Type</th><th>Product</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($recent as $row)
                    <tr>
                        <td>{{ $row['created_at']->diffForHumans() }}</td>
                        <td><a href="{{ route('admin.leads.show', ['type' => $row['type'], 'id' => $row['id']]) }}">{{ $row['name'] }}</a><small>{{ $row['organization'] }}</small></td>
                        <td>{{ $row['type'] === 'demos' ? 'Demo' : 'Quote' }}</td>
                        <td>{{ $row['product'] ?? '-' }}</td>
                        <td>@include('admin._badge', ['status' => $row['status'], 'label' => ucfirst($row['status'])])</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>
    <section class="card">
        <header class="card-head"><h2>Most viewed pages</h2></header>
        @include('admin.analytics._bars', ['rows' => $topPages, 'total' => max(1, $s['pageviews'])])
    </section>
</div>
@endsection

@push('vendor')<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>@endpush
@push('scripts')<script src="{{ asset('js/admin-charts.js') }}?v=1"></script>@endpush
