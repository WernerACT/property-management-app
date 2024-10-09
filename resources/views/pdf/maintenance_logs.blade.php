<!-- resources/views/pdf/rental_schedule.blade.php -->
@include('pdf.header', ['title' => $title, 'description' => $description])

<!-- Table or body-specific content goes here -->
<table>
    <thead>
    <tr>
        <th>Property</th>
        <th>Vendor</th>
        <th>Item Maintained</th>
        <th>Action</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Status</th>
        <th>Comment</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        <tr>
            <td>{{ $log->property->name }}</td>
            <td>{{ $log->vendor->display_name }}</td>
            <td>{{ $log->maintenanceItem->name }}</td>
            <td>{{ ucfirst($log->action) }}</td>
            <td>{{ number_format($log->amount, 2) }}</td>
            <td>{{ \Carbon\Carbon::parse($log->date)->format('F j, Y') }}</td>
            <td>{{ ucfirst($log->status) }}</td>
            <td>{{ $log->comment }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@include('pdf.footer', ['user' => $user])
