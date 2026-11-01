{{-- Params: $range, $from, $to, $action --}}
<form class="range" method="GET" action="{{ $action }}" data-range-form>
    <div class="seg" role="group" aria-label="Date range">
        @foreach(['today' => 'Today', '7d' => '7 days', '30d' => '30 days', '90d' => '90 days'] as $key => $label)
            <a href="{{ $action }}?range={{ $key }}" @class(['is-active' => $range === $key])>{{ $label }}</a>
        @endforeach
    </div>
    <input type="hidden" name="range" value="custom">
    <input type="date" name="from" value="{{ $from->toDateString() }}" max="{{ now()->toDateString() }}" aria-label="From">
    <span class="muted">to</span>
    <input type="date" name="to" value="{{ $to->toDateString() }}" max="{{ now()->toDateString() }}" aria-label="To">
    <button class="btn btn-outline btn-sm" type="submit">Apply</button>
</form>
