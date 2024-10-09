<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalScheduleResource\Pages;
use App\Models\Entity;
use App\Models\Property;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class RentalScheduleResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $modelLabel = 'Rental Schedule';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entity.name')
                    ->label('Entity'),
                TextColumn::make('name')
                    ->label('Property'),
                TextColumn::make('levy_water_electricity') // Calculated sum for Levy, Water, Electricity
                ->label('Levy + Water + Electricity')
                    ->getStateUsing(fn (Property $record) => $record->calculateExpenses(['Levy', 'Water', 'Electricity'])),
                TextColumn::make('property_rates')
                    ->label('Property Rates')
                    ->getStateUsing(fn (Property $record) => $record->calculateExpenses(['Property Rates'])),
                TextColumn::make('rental_commission')
                    ->label('Rental Commission')
                    ->getStateUsing(fn (Property $record) => $record->calculateExpenses(['Rental Commission'])),
                TextColumn::make('loan_repayment')
                    ->label('Loan Repayment')
                    ->getStateUsing(fn (Property $record) => $record->calculateExpenses(['Loan Repayment'])),
                TextColumn::make('total_expenses')
                    ->label('Total Expenses')
                    ->getStateUsing(fn (Property $record) => $record->calculateTotalExpenses()),

                TextColumn::make('rental_income')
                    ->label('Rental Income')
                    ->getStateUsing(fn (Property $record) => $record->calculateIncome(['Rental Income'])),
                TextColumn::make('municipal_income')
                    ->label('Municipal Income')
                    ->getStateUsing(fn (Property $record) => $record->calculateIncome(['Municipal Income'])),
                TextColumn::make('total_income')
                    ->label('Total Income')
                    ->getStateUsing(fn (Property $record) => $record->calculateTotalIncome()),

                TextColumn::make('profit')
                    ->label('Profit')
                    ->getStateUsing(fn (Property $record) => $record->calculateProfit()),

                TextColumn::make('current_value')
                    ->label('Property Value')
                    ->getStateUsing(fn (Property $record) => $record->current_value),
            ])
            ->filters([
                SelectFilter::make('name')
                    ->label('Entity')
                    ->multiple()
                    ->relationship('entity', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->placeholder('Select start date')
                            ->maxDate(Carbon::now()),
                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->placeholder('Select end date')
                            ->maxDate(Carbon::now()),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['start_date'])) {
                            $query->whereHas('transactions', function (Builder $subQuery) use ($data) {
                                $subQuery->whereDate('date', '>=', Carbon::parse($data['start_date']));
                            });
                        }

                        if (!empty($data['end_date'])) {
                            $query->whereHas('transactions', function (Builder $subQuery) use ($data) {
                                $subQuery->whereDate('date', '<=', Carbon::parse($data['end_date']));
                            });
                        }

                        return $query;
                    })
                    ->label('Date Range'),
            ])
            ->headerActions([
                Action::make('generate_pdf')
                    ->label('Generate PDF')
                    ->icon('heroicon-o-document-text')
                    ->form(function () {

                        $firstDayOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                        $lastDayOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

                        return [
                            Select::make('entity_id')
                                ->label('Entity')
                                ->placeholder('Select Entity or All Entities')
                                ->options(function () {
                                    return Entity::pluck('name', 'id')->prepend('All Entities', 'all')->toArray();  // "All" option
                                })
                                ->searchable()
                                ->default('all')
                                ->preload()
                                ->required(),

                            DatePicker::make('start_date')
                                ->label('Start Date')
                                ->placeholder('Select start date')
                                ->default($firstDayOfLastMonth)
                                ->required(),

                            DatePicker::make('end_date')
                                ->label('End Date')
                                ->placeholder('Select end date')
                                ->default($lastDayOfLastMonth)
                                ->required(),
                        ];
                    })
                    ->action(function ($data) {
                        // Step 1: Prepare the query
                        $query = Property::query();

                        // Filter by entity if not "all"
                        if ($data['entity_id'] !== 'all') {
                            $query->where('entity_id', $data['entity_id']);
                        }

                        // Filter by date range
                        if (!empty($data['start_date'])) {
                            $startDate = Carbon::parse($data['start_date'])->startOfDay();
                            $query->whereHas('transactions', function (Builder $subQuery) use ($startDate) {
                                $subQuery->where('date', '>=', $startDate);
                            });
                        }

                        if (!empty($data['end_date'])) {
                            $endDate = Carbon::parse($data['end_date'])->endOfDay();
                            $query->whereHas('transactions', function (Builder $subQuery) use ($endDate) {
                                $subQuery->where('date', '<=', $endDate);
                            });
                        }

                        $properties = $query->get();

                        // Step 2: Generate the PDF with the queried data
                        $pdf = Pdf::loadView('pdf.rental_schedule', [
                            'title' => 'Rental Schedule',
                            'description' => 'Generated between the dates ' . $startDate . ' and ' . $endDate,
                            'properties' => $properties,
                            'user' => Auth::user(),
                            ]);

                        // Step 3: Return the PDF for download
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'Rental Schedule from ' . $startDate . ' to ' . $endDate . '.pdf');
                    })
                    ->requiresConfirmation(),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalSchedules::route('/'),
        ];
    }
}
