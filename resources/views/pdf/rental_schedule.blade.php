<!-- resources/views/pdf/rental_schedule.blade.php -->
@include('pdf.header', ['title' => $title, 'description' => $description])

<!-- Table or body-specific content goes here -->
<table>
    <thead>
    <tr>
        <th>Entity</th>
        <th>Property</th>
        <th>Levy + Water + Electricity</th>
        <th>Property Rates</th>
        <th>Rental Commission</th>
        <th>Loan Repayment</th>
        <th>Total Expenses</th>
        <th>Rental Income</th>
        <th>Municipal Income</th>
        <th>Total Income</th>
        <th>Profit</th>
        <th>Property Value</th>
    </tr>
    </thead>
    <tbody>
    @foreach($properties as $property)
        <tr>
            <td>{{ $property->entity->name }}</td>
            <td>{{ $property->name }}</td>
            <td>{{ number_format($property->calculateExpenses(['Levy', 'Water', 'Electricity']), 2) }}</td>
            <td>{{ number_format($property->calculateExpenses(['Property Rates']), 2) }}</td>
            <td>{{ number_format($property->calculateExpenses(['Rental Commission']), 2) }}</td>
            <td>{{ number_format($property->calculateExpenses(['Loan Repayment']), 2) }}</td>
            <td>{{ number_format($property->calculateTotalExpenses(), 2) }}</td>
            <td>{{ number_format($property->calculateIncome(['Rental Income']), 2) }}</td>
            <td>{{ number_format($property->calculateIncome(['Municipal Income']), 2) }}</td>
            <td>{{ number_format($property->calculateTotalIncome(), 2) }}</td>
            <td>{{ number_format($property->calculateProfit(), 2) }}</td>
            <td>{{ number_format($property->current_value, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@include('pdf.footer', ['user' => $user])
