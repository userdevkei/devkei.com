{{-- Params: $rows (label, value, optional meta), $total, optional $empty --}}
<ul class="bars">
    @forelse($rows as $row)
        @php $pct = $total > 0 ? round($row['value'] / $total * 100, 1) : 0; @endphp
        <li>
            <div class="bar-top">
                <span class="bar-label" title="{{ $row['label'] }}">{{ $row['label'] }}@if(! empty($row['meta']))<small>{{ $row['meta'] }}</small>@endif</span>
                <span class="bar-val">{{ number_format($row['value']) }} <small>{{ $pct }}%</small></span>
            </div>
            <div class="bar-track"><span style="width:{{ min(100, $pct) }}%"></span></div>
        </li>
    @empty
        <li class="muted">{{ $empty ?? 'No data for this period yet.' }}</li>
    @endforelse
</ul>
