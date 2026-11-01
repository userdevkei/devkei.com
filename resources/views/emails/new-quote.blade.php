@php
    $hosting = ['self_host' => 'Self-hosted', 'devkei_host' => 'DevKei hosted, one-time licence', 'lease' => 'DevKei hosted, leased'];
@endphp
<div style="font-family:Arial,sans-serif;font-size:15px;color:#13243A;max-width:560px">
    <h2 style="margin:0 0 12px">New quote request</h2>
    <table cellpadding="6" style="border-collapse:collapse;width:100%">
        <tr><td><b>Name</b></td><td>{{ $lead->name }}</td></tr>
        <tr><td><b>Organisation</b></td><td>{{ $lead->organization }} ({{ $lead->organization_type ?: 'type not given' }})</td></tr>
        <tr><td><b>Email</b></td><td>{{ $lead->email }}</td></tr>
        <tr><td><b>Phone</b></td><td>{{ $lead->phone }}</td></tr>
        <tr><td><b>Product</b></td><td>{{ $lead->product?->name ?? 'Not sure yet' }}</td></tr>
        <tr><td><b>Users</b></td><td>{{ $lead->user_count ?: '-' }}</td></tr>
        <tr><td><b>Hosting</b></td><td>{{ $hosting[$lead->hosting_preference] ?? 'Not sure yet' }}</td></tr>
        <tr><td><b>Budget</b></td><td>{{ $lead->budget_range ?: '-' }}</td></tr>
        <tr><td valign="top"><b>Requirements</b></td><td>{!! nl2br(e($lead->requirements)) !!}</td></tr>
    </table>
    <p><a href="{{ route('admin.leads.show', ['type' => 'quotes', 'id' => $lead->id]) }}">Open in admin</a></p>
</div>
