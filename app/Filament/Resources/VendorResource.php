<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Models\Vendor;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $slug = 'vendors';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('vendor_type_id')
                    ->relationship('vendorType', 'name')
                    ->searchable()
                    ->required()->preload(),

                TextInput::make('display_name')
                    ->label('Company Name')
                    ->required(),

                TextInput::make('name')
                    ->label('Contact Person'),

                TextInput::make('email')
                    ->email()
                    ->required(),

                TextInput::make('address')
                    ->label('Address')
                    ->required(),

                TextInput::make('mobile_number'),

                TextInput::make('office_number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendorType.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('display_name')
                    ->label("Company Name")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Contact Person')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile_number'),

                TextColumn::make('office_number'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'vendorType.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->vendorType) {
            $details['VendorType'] = $record->vendorType->name;
        }

        return $details;
    }
}
