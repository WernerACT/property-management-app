<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $slug = 'tenants';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tenant_type_id')
                    ->relationship('tenantType', 'name')
                    ->label('Tenant Type')
                    ->required(),

                Select::make('property_id')
                    ->relationship('property', 'name')
                    ->label('Property')
                    ->required(),

                TextInput::make('display_name')
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('surname')
                    ->required(),

                TextInput::make('email')
                    ->required(),

                TextInput::make('mobile_number')
                    ->required(),

                TextInput::make('office_number'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('surname'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile_number'),

                TextColumn::make('office_number'),

                TextColumn::make('property.name'),
            ])
            ->filters([
                SelectFilter::make('property')
                    ->label('Property')
                    ->multiple()
                    ->relationship('property', 'name')->searchable()->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'address.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->address) {
            $details['Address'] = $record->address->name;
        }

        return $details;
    }
}
