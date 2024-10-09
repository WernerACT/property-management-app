<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceLogResource\Pages;
use App\Filament\Resources\MaintenanceLogResource\RelationManagers;
use App\Models\MaintenanceLog;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MaintenanceLogResource extends Resource
{
    protected static ?string $model = MaintenanceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->label('Property')
                    ->relationship('property', 'name')
                    ->required(),
                Forms\Components\Select::make('vendor_id')
                    ->label('Vendor')
                    ->relationship('vendor', 'name')
                    ->required(),
                Forms\Components\Select::make('maintenance_item_id')
                    ->label('Item Maintained')
                    ->relationship('maintenanceItem', 'name')
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Maintenance Item Name'),
                    ]),
                Select::make('action')
                    ->options([
                        'repaired' => 'Repaired',
                        'replaced' => 'Replaced'
                    ])->default('replaced')->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'rejected' => 'Rejected',
                    ])->default('paid')->required(),
                DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('comment')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('vendor.display_name')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('maintenanceItem.name')
                    ->label('Item Maintained')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => ucfirst($state)),
            ])
            ->filters([
                SelectFilter::make('property')
                    ->label('Property')
                    ->multiple()
                    ->relationship('property', 'name')->searchable()->preload(),
                SelectFilter::make('vendor')
                    ->label('Vendor')
                    ->multiple()
                    ->relationship('vendor', 'name')->searchable()->preload(),
                SelectFilter::make('maintenance_item')
                    ->label('Item')
                    ->multiple()
                    ->relationship('maintenanceItem', 'name')->searchable()->preload(),
            ])
            ->headerActions([
                Action::make('generate_pdf')
                    ->label('Generate PDF')
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
                        // Step 1: Prepare the query to filter maintenance logs based on form input
                        $query = MaintenanceLog::query();

                        // Filter by property if not "all"
                        if ($data['property_id'] !== 'all') {
                            $query->where('property_id', $data['property_id']);
                        }

                        // Filter by start and end date
                        if (!empty($data['start_date'])) {
                            $startDate = Carbon::parse($data['start_date'])->startOfDay();
                            $query->where('date', '>=', $startDate);
                        }

                        if (!empty($data['end_date'])) {
                            $endDate = Carbon::parse($data['end_date'])->endOfDay();
                            $query->where('date', '<=', $endDate);
                        }

                        $maintenanceLogs = $query->get();

                        // Step 2: Generate the PDF using the queried data
                        $pdf = Pdf::loadView('pdf.maintenance_logs', [
                            'title' => 'Maintenance Logs',
                            'description' => 'Generated between the dates ' . $startDate . ' and ' . $endDate,
                            'logs' => $maintenanceLogs,
                            'user' => Auth::user(), // For displaying the user info in the PDF
                        ]);

                        // Step 3: Return the PDF for download
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'Maintenance Logs from ' . $startDate . ' to ' . $endDate . '.pdf');
                    })
                    ->requiresConfirmation(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMaintenanceLogs::route('/'),
            'create' => Pages\CreateMaintenanceLog::route('/create'),
            'edit' => Pages\EditMaintenanceLog::route('/{record}/edit'),
        ];
    }
}
