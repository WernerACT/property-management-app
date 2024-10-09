<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Property;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'name')
                    ->required(),
                Forms\Components\Select::make('transaction_status_id')
                    ->relationship('transactionStatus', 'name')
                    ->hiddenOn('create')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('transaction_type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ])->label('Income or Expense')
                    ->required(),
                Forms\Components\Select::make('transaction_type_id')
                    ->relationship('transactionType', 'name')
                    ->required(),
                Forms\Components\TextInput::make('comment')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_recurring')
                    ->label('Recurring Transaction')
                    ->default(false)
                    ->reactive(),
                Forms\Components\Select::make('recurring_interval')
                    ->label('Recurring Interval')
                    ->options([
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                    ])
                    ->visible(fn($get) => $get('is_recurring'))
                    ->required(fn($get) => $get('is_recurring')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transactionType.name')
                    ->label('Transaction')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('transaction_status_id')
                    ->label('Status')
                    ->options(TransactionStatus::all()->pluck('name', 'id')->toArray())
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('comment')->limit(20),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => $record->transaction_type === 'expense' ? '- ' . number_format($record->amount, 2) : number_format($record->amount, 2))
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('property')
                    ->label('Properties')
                    ->multiple()
                    ->relationship('property', 'name')->searchable()->preload(),
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->placeholder('Select start date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->placeholder('Select end date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['start_date'])) {
                            $query->whereDate('date', '>=', $data['start_date']);
                        }

                        if (!empty($data['end_date'])) {
                            $query->whereDate('date', '<=', $data['end_date']);
                        }

                        return $query;
                    })
                    ->label('Date Range')
            ])
            ->persistFiltersInSession()
            ->headerActions([
                Action::make('generate_pdf')
                    ->label('Generate Profit/Loss Report')
                    ->icon('heroicon-o-document-text')
                    ->form(function () {
                        $firstDayOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                        $lastDayOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

                        return [
                            Select::make('property_id')
                                ->label('Property')
                                ->placeholder('Select Property or All Properties')
                                ->options(Property::pluck('name', 'id')->prepend('All Properties', 'all'))
                                ->searchable()
                                ->preload()
                                ->default('all')
                                ->required(),

                            DatePicker::make('start_date')
                                ->label('Start Date')
                                ->default($firstDayOfLastMonth)
                                ->required(),

                            DatePicker::make('end_date')
                                ->label('End Date')
                                ->default($lastDayOfLastMonth)
                                ->required(),
                        ];
                    })
                    ->action(function ($data) {
                        // Step 1: Prepare the query for transactions
                        $query = Transaction::query();

                        // Filter by property if not "all"
                        if ($data['property_id'] !== 'all') {
                            $query->where('property_id', $data['property_id']);
                        }

                        // Filter by date range
                        if (!empty($data['start_date'])) {
                            $startDate = Carbon::parse($data['start_date'])->startOfDay();
                            $query->where('date', '>=', $startDate);
                        }

                        if (!empty($data['end_date'])) {
                            $endDate = Carbon::parse($data['end_date'])->endOfDay();
                            $query->where('date', '<=', $endDate);
                        }

                        $query->where('transaction_status_id', 3);

                        $transactions = $query->get();

                        // Step 2: Calculate total income, total expenses, and profit/loss
                        $totalIncome = $transactions->where('transaction_type', 'income')->sum('amount');
                        $totalExpense = $transactions->where('transaction_type', 'expense')->sum('amount');
                        $totalProfit = $totalIncome - $totalExpense;

                        // Step 3: Generate the PDF
                        $pdf = Pdf::loadView('pdf.profit_loss_report', [
                            'title' => 'Profit / Loss Report',
                            'description' => 'Generated between the dates ' . $startDate . ' and ' . $endDate,
                            'transactions' => $transactions,
                            'totalIncome' => $totalIncome,
                            'totalExpense' => $totalExpense,
                            'totalProfit' => $totalProfit,
                            'user' => Auth::user(),
                            'startDate' => Carbon::parse($data['start_date'])->format('F j, Y'),
                            'endDate' => Carbon::parse($data['end_date'])->format('F j, Y'),
                        ]);

                        // Return the PDF for download
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'profit_loss_report.pdf');
                    })
                    ->requiresConfirmation(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TransactionResource\RelationManagers\DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TransactionResource\Widgets\TransactionOverview::class,
        ];
    }

    public static function create(array $data)
    {
        $transaction = Transaction::create([
            // Transaction fields
            'property_id' => $data['property_id'],
            'transaction_type_id' => $data['transaction_type_id'],
            'transaction_status_id' => $data['transaction_status_id'] ?? 1,  // Default to pending
            'amount' => $data['amount'],
            'transaction_type' => $data['transaction_type'],
            'date' => $data['date'],
            'comment' => $data['comment'],
        ]);

        // If the transaction is recurring, create a recurring transaction entry
        if ($data['is_recurring']) {
            RecurringTransaction::create([
                'transaction_id' => $transaction->id,
                'recurring_interval' => $data['recurring_interval'],
                'next_run_date' => Carbon::parse($data['date'])->addDays(1),  // Start next run 1 day after
            ]);
        }

        return $transaction;
    }
}
