@extends('admin.layout')
@section('title', $cfg['title'])
@section('heading', $cfg['title'])
@section('subtitle', $type === 'demos' ? 'People who asked to see a system in action.' : 'People who asked for a written quote.')

@section('content')
<div class="chip-bar" data-dt-filter="#leads-table" data-column="6">
    <button type="button" class="chip is-active" data-value="">All <b>{{ $leads->count() }}</b></button>
    @foreach($cfg['statuses'] as $key => $label)
        <button type="button" class="chip" data-value="{{ $key }}">{{ $label }} <b>{{ $counts[$key] ?? 0 }}</b></button>
    @endforeach
</div>

<section class="card card-table">
    <table id="leads-table" class="dt" style="width:100%"
        data-config="{{ json_encode(['order' => [[0, 'desc']], 'export' => true, 'noSort' => [7], 'exportCols' => [0, 1, 2, 3, 4, 5, 6], 'pageLength' => 25]) }}">
        <thead>
            <tr>
                <th>Received</th><th>Contact</th><th>Organisation</th><th>Product</th>
                <th>{{ $type === 'demos' ? 'Preferred slot' : 'Users' }}</th><th>Source</th><th>Status</th><th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($leads as $lead)
            <tr>
                <td data-order="{{ $lead->created_at->timestamp }}">{{ $lead->created_at->format('d M Y, H:i') }}</td>
                <td><b>{{ $lead->name }}</b><small>{{ $lead->email }}</small></td>
                <td>{{ $lead->organization }}<small>{{ $lead->organization_type }}</small></td>
                <td>{{ $lead->product?->name ?? '-' }}</td>
                <td>
                    @if($type === 'demos')
                        {{ $lead->preferred_date?->format('d M Y') ?? 'Any date' }}<small>{{ $lead->preferred_time }}</small>
                    @else
                        {{ $lead->user_count ?: '-' }}
                    @endif
                </td>
                <td>{{ $lead->source ?: 'Unknown' }}<small>{{ $lead->medium }}</small></td>
                <td data-search="{{ $lead->status }}">@include('admin._badge', ['status' => $lead->status, 'label' => $cfg['statuses'][$lead->status] ?? ucfirst($lead->status)])</td>
                <td class="cell-actions"><a class="btn btn-outline btn-sm" href="{{ route('admin.leads.show', ['type' => $type, 'id' => $lead->id]) }}">Open</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
