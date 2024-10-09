<?php

namespace App\Filament\Resources\TransactionDocumentResource\Pages;

use App\Filament\Resources\TransactionDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionDocuments extends ListRecords
{
    protected static string $resource = TransactionDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
