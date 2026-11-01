@php
    $cls = match ($status) {
        'new' => 'badge-blue',
        'completed', 'won' => 'badge-green',
        'cancelled', 'lost' => 'badge-gray',
        default => 'badge-amber',
    };
@endphp
<span class="badge {{ $cls }}">{{ $label }}</span>
