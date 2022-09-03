<?php

namespace Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;

class EditCustomField extends EditRecord
{
    protected static string $resource = CustomFieldResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
