@extends('admin.layout')
@section('title', 'Visitor log')
@section('heading', 'Visitor log')
@section('subtitle', 'Every page view, newest first. Search by page, source, country, browser or device.')
@section('actions')
    @include('admin.analytics._range', ['action' => route('admin.analytics.visitors')])
@endsection

@section('content')
<section class="card card-table">
    <table class="dt" style="width:100%"
        data-config="{{ json_encode([
            'serverSide' => true,
            'ajax' => route('admin.analytics.visitors.data', request()->only('range', 'from', 'to')),
            'columns' => 9,
            'order' => [[0, 'desc']],
            'export' => false,
            'pageLength' => 100,
        ]) }}" >
        <thead>
            <tr><th>#</th><th>Time</th><th>Page</th><th>Source</th><th>Location</th><th>Device</th><th>Browser</th><th>System</th><th>Visitor</th></tr>
        </thead>
    </table>
</section>
@endsection
