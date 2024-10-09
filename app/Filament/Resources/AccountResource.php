<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
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

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $slug = 'accounts';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('account_type_id')
                    ->relationship('accountType', 'name')
                    ->searchable()
                    ->required(),

                Select::make('bank_account_type_id')
                    ->relationship('bankAccountType', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('bank')
                    ->required(),

                TextInput::make('account_number')
                    ->required(),

                TextInput::make('branch_code')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accountType.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bankAccountType.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bank'),

                TextColumn::make('account_number'),

                TextColumn::make('branch_code'),
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['accountType.name', 'bankAccountType.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->accountType) {
            $details['AccountType'] = $record->accountType->name;
        }

        if ($record->bankAccountType) {
            $details['BankAccountType'] = $record->bankAccountType->name;
        }

        return $details;
    }
}
