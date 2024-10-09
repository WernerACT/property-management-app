<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionDocumentResource\Pages;
use App\Filament\Resources\TransactionDocumentResource\RelationManagers;
use App\Models\TransactionDocument;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionDocumentResource extends Resource
{
    protected static ?string $model = TransactionDocument::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->label('Transaction')
                    ->required(),
                FileUpload::make('document_path')
                    ->label('Document')
                    ->directory('transaction-documents')
                    ->openable()
                    ->downloadable()
                    ->acceptedFileTypes(['application/pdf']) // Restrict to PDF files
                    ->required(),
                TextInput::make('document_name')
                    ->label('Document Name')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction.id')
                    ->label('Transaction ID'),
                TextColumn::make('document_name')
                    ->label('Document Name'),
                TextColumn::make('document_path')
                    ->label('Document Path')
                    ->url(fn ($record) => Storage::url($record->document_path)),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTransactionDocuments::route('/'),
            'create' => Pages\CreateTransactionDocument::route('/create'),
            'edit' => Pages\EditTransactionDocument::route('/{record}/edit'),
        ];
    }
}
