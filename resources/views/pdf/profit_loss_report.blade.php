<!-- resources/views/pdf/rental_schedule.blade.php -->
@include('pdf.header', ['title' => $title, 'description' => $description])

<!-- Table or body-specific content goes here -->
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Property</th>
        <th>Transaction Type</th> <!-- Relationship -->
        <th>Income/Expense</th> <!-- Column -->
        <th>Amount</th>
        <th>Comment</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($transactions as $transaction)
        <tr>
            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') }}</td>
            <td>{{ $transaction->property->name }}</td>
            <!-- Use transaction_type.name to access the relationship -->
            <td>{{ ucfirst($transaction->transactionType->name) }}</td>
            <!-- Use transaction_type column to access the type (income/expense) -->
            <td>{{ ucfirst($transaction->transaction_type) }}</td>
            <td>{{ number_format($transaction->amount, 2) }} ZAR</td>
            <td>{{ $transaction->comment }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4" style="text-align: right;">Total Income:</th>
        <td colspan="2">{{ number_format($totalIncome, 2) }} ZAR</td>
    </tr>
    <tr>
        <th colspan="4" style="text-align: right;">Total Expenses:</th>
        <td colspan="2">{{ number_format($totalExpense, 2) }} ZAR</td>
    </tr>
    <tr class="{{ $totalProfit >= 0 ? 'profit' : 'loss' }}">
        <th colspan="4" style="text-align: right;">Total Profit/Loss:</th>
        <td colspan="2">{{ number_format($totalProfit, 2) }} ZAR</td>
    </tr>
    </tfoot>
</table>

@include('pdf.footer', ['user' => $user])
