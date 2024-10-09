<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_type_id')
                    ->label('Property Type')
                    ->relationship('property_type', 'name')
                    ->required(),
                Forms\Components\Select::make('entity_id')
                    ->label('Entity')
                    ->relationship('entity', 'name')
                    ->required(),
                Forms\Components\Select::make('property_status_id')
                    ->label('Status')
                    ->relationship('propertyStatus', 'name')
                    ->required(),
                Forms\Components\Select::make('managing_agent_id')
                    ->label('Managing Agent')
                    ->relationship('managingAgent', 'display_name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('purchase_date')
                    ->required(),
                Forms\Components\TextInput::make('purchase_value')
                    ->label('Purchase Value')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('current_value')
                    ->label('Current Value')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('street_address')
                    ->label('Street Address')
                    ->string()
                    ->required(),
                Forms\Components\TextInput::make('address_line_2')
                    ->label('Address Line 2')
                    ->string(),
                Forms\Components\TextInput::make('suburb')
                    ->label('Suburb')
                    ->string()
                    ->required(),
                Forms\Components\TextInput::make('city')
                    ->label('City')
                    ->string()
                    ->required(),
                Forms\Components\TextInput::make('province')
                    ->label('Province')
                    ->string()
                    ->required(),
                Forms\Components\TextInput::make('postal_code')
                    ->label('Postal Code')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('entity.name')
                    ->label('Entity')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('propertyStatus.name')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_value')
                    ->label('Property Value')
                    ->money('ZAR')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('entity')
                    ->label('Owned By')
                    ->multiple()
                    ->relationship('entity', 'name')->searchable()->preload(),
                SelectFilter::make('property_status')
                    ->label('Status')
                    ->multiple()
                    ->relationship('propertyStatus', 'name')->searchable()->preload(),
                SelectFilter::make('property')
                    ->label('Property')
                    ->options(Property::pluck('name', 'id')->toArray()) // Pull the list of property names and IDs
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->where('id', $data['value']); // Correctly pass the selected property ID
                        }
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('filter_by_date')
                    ->label('Filter by Date')
                    ->action(function (array $data) {
                        // Store the start and end dates in session or pass them to the widget
                        session([
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date'],
                        ]);
                    })
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->default(session('start_date'))
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->default(session('end_date'))
                            ->required()
                            ->minDate(fn (callable $get) => $get('start_date')),
                    ]),
                Tables\Actions\Action::make('clear_dates')
                    ->label('Clear Dates')
                    ->action(function () {
                        session()->forget(['start_date', 'end_date']);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PropertyResource\RelationManagers\TransactionsRelationManager::class,
            PropertyResource\RelationManagers\TenantsRelationManager::class,
            PropertyResource\RelationManagers\DescriptionRelationManager::class,
            PropertyResource\RelationManagers\ImagesRelationManager::class,
            PropertyResource\RelationManagers\DocumentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PropertyResource\Widgets\PropertyOverview::class,
        ];
    }
}
