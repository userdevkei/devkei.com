@extends('admin.layout')
@section('title', $cfg['singular'] . ' from ' . $lead->organization)
@section('heading', $lead->name)
@section('subtitle', $lead->organization . ' · ' . $cfg['singular'] . ' received ' . $lead->created_at->format('d M Y, H:i'))

@section('actions')
    <a class="btn btn-outline btn-sm" href="{{ route('admin.leads.index', $type) }}">Back to list</a>
    <a class="btn btn-outline btn-sm" href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}"><x-admin.icon name="phone" :size="16"/> Call</a>
    <a class="btn btn-primary btn-sm" href="mailto:{{ $lead->email }}"><x-admin.icon name="mail" :size="16"/> Reply</a>
@endsection

@php
    $hosting = ['self_host' => 'Self-hosted', 'devkei_host' => 'DevKei hosted, one-time licence', 'lease' => 'DevKei hosted, leased'];
@endphp

@section('content')
<div class="grid-side">
    <div class="stack">
        <section class="card">
            <header class="card-head"><h2>Request</h2>@include('admin._badge', ['status' => $lead->status, 'label' => $cfg['statuses'][$lead->status] ?? ucfirst($lead->status)])</header>
            <dl class="facts-list">
                <div><dt>Name</dt><dd>{{ $lead->name }}</dd></div>
                <div><dt>Email</dt><dd><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></dd></div>
                <div><dt>Phone</dt><dd><a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}">{{ $lead->phone }}</a></dd></div>
                <div><dt>Organisation</dt><dd>{{ $lead->organization }}{{ $lead->organization_type ? ' (' . $lead->organization_type . ')' : '' }}</dd></div>
                <div><dt>Product</dt><dd>{{ $lead->product?->name ?? 'Not sure yet' }}</dd></div>
                @if($type === 'demos')
                    <div><dt>Preferred slot</dt><dd>{{ $lead->preferred_date?->format('D, d M Y') ?? 'Any date' }}, {{ $lead->preferred_time ?: 'any time' }}</dd></div>
                    <div><dt>Message</dt><dd>{!! nl2br(e($lead->message ?: '-')) !!}</dd></div>
                @else
                    <div><dt>Users</dt><dd>{{ $lead->user_count ?: '-' }}</dd></div>
                    <div><dt>Hosting</dt><dd>{{ $hosting[$lead->hosting_preference] ?? 'Not sure yet' }}</dd></div>
                    <div><dt>Budget</dt><dd>{{ $lead->budget_range ?: '-' }}</dd></div>
                    <div><dt>Requirements</dt><dd>{!! nl2br(e($lead->requirements)) !!}</dd></div>
                @endif
            </dl>
        </section>

        <section class="card">
            <header class="card-head"><h2>Where they came from</h2></header>
            @if($lead->source || $lead->landing_path || $lead->country_code)
                <dl class="facts-list">
                    <div><dt>Source</dt><dd>{{ $lead->source ?: 'Unknown' }}{{ $lead->medium ? ' (' . $lead->medium . ')' : '' }}</dd></div>
                    @if($lead->campaign)<div><dt>Campaign</dt><dd>{{ $lead->campaign }}</dd></div>@endif
                    <div><dt>Landing page</dt><dd><code>{{ $lead->landing_path ?: '-' }}</code></dd></div>
                    <div><dt>Country</dt><dd>{{ $lead->country_code ? \App\Models\PageView::countryName($lead->country_code) : 'Unknown' }}</dd></div>
                </dl>
            @else
                <p class="empty">No visit data for this request. It was sent before tracking started, or the visitor blocks cookies.</p>
            @endif

            @if($journey->isNotEmpty())
                <h3 class="card-sub">Pages they viewed</h3>
                <ol class="timeline">
                    @foreach($journey as $view)
                        <li>
                            <time>{{ $view->created_at->format('d M, H:i') }}</time>
                            <span><code>{{ $view->path }}</code>@if($view->is_entry) <em class="badge badge-blue">Visit started via {{ $view->source ?: 'Direct' }}</em>@endif</span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <header class="card-head"><h2>Follow-up</h2></header>
            <form method="POST" action="{{ route('admin.leads.update', ['type' => $type, 'id' => $lead->id]) }}">
                @csrf @method('PUT')
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        @foreach($cfg['statuses'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $lead->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="margin-top:1rem">
                    <label for="admin_notes">Internal notes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="6" placeholder="Calls made, what was agreed, next steps.">{{ old('admin_notes', $lead->admin_notes) }}</textarea>
                    @error('admin_notes')<p class="error">{{ $message }}</p>@enderror
                </div>
                <button class="btn btn-primary btn-block" type="submit">Save changes</button>
            </form>
        </section>

        <section class="card card-danger">
            <header class="card-head"><h2>Delete</h2></header>
            <p class="muted">Removes this request permanently.</p>
            <form method="POST" action="{{ route('admin.leads.destroy', ['type' => $type, 'id' => $lead->id]) }}"
                  data-confirm="{{ json_encode(['title' => 'Delete this request?', 'text' => 'This cannot be undone.']) }}">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-block" type="submit"><x-admin.icon name="trash" :size="16"/> Delete request</button>
            </form>
        </section>
    </div>
</div>
@endsection
