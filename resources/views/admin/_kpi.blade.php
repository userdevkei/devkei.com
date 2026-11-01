{{-- Params: $label, $value, $icon, optional $delta (percent, null = hide), $lowerBetter, $hint --}}
@php
    $lowerBetter = $lowerBetter ?? false;
    $delta = $delta ?? null;
    $good = $delta === null ? null : ($lowerBetter ? $delta <= 0 : $delta >= 0);
@endphp
<div class="kpi">
    <div class="kpi-top">
        <span class="kpi-label">{{ $label }}</span>
        <span class="kpi-icon"><x-admin.icon :name="$icon" :size="18"/></span>
    </div>
    <div class="kpi-value">{{ $value }}</div>
    <div class="kpi-foot">
        @if($delta !== null)
            <span class="delta {{ $good ? 'delta-good' : 'delta-bad' }}"><x-admin.icon :name="$delta >= 0 ? 'up' : 'down'" :size="12"/> {{ abs($delta) }}%</span>
            <span class="muted">vs previous period</span>
        @elseif(! empty($hint))
            <span class="muted">{{ $hint }}</span>
        @endif
    </div>
</div>
