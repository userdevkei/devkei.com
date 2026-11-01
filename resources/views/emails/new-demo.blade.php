<div style="font-family:Arial,sans-serif;font-size:15px;color:#13243A;max-width:560px">
    <h2 style="margin:0 0 12px">New demo request</h2>
    <table cellpadding="6" style="border-collapse:collapse;width:100%">
        <tr><td><b>Name</b></td><td>{{ $lead->name }}</td></tr>
        <tr><td><b>Organisation</b></td><td>{{ $lead->organization }} ({{ $lead->organization_type ?: 'type not given' }})</td></tr>
        <tr><td><b>Email</b></td><td>{{ $lead->email }}</td></tr>
        <tr><td><b>Phone</b></td><td>{{ $lead->phone }}</td></tr>
        <tr><td><b>Product</b></td><td>{{ $lead->product?->name ?? 'Not sure yet' }}</td></tr>
        <tr><td><b>Preferred slot</b></td><td>{{ $lead->preferred_date?->format('D, d M Y') ?? 'Any date' }}, {{ $lead->preferred_time ?: 'any time' }}</td></tr>
        <tr><td valign="top"><b>Message</b></td><td>{!! nl2br(e($lead->message ?: '-')) !!}</td></tr>
    </table>
    <p><a href="{{ route('admin.leads.show', ['type' => 'demos', 'id' => $lead->id]) }}">Open in admin</a></p>
</div>
