<?php

namespace Yemenpoint\FilamentCustomFields\Resources\CustomFieldResponseResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResponseResource;

class EditCustomFieldResponse extends EditRecord
{
    protected static string $resource = CustomFieldResponseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
