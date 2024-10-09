<?php

namespace App\Filament\Resources\BankAccountTypeResource\Pages;

use App\Filament\Resources\BankAccountTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBankAccountTypes extends ListRecords
{
    protected static string $resource = BankAccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
