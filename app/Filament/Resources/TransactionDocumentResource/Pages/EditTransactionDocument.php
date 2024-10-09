<?php

namespace App\Filament\Resources\TransactionDocumentResource\Pages;

use App\Filament\Resources\TransactionDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionDocument extends EditRecord
{
    protected static string $resource = TransactionDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
