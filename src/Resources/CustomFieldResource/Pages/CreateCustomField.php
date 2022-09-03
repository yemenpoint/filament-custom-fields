<?php

namespace Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;

class CreateCustomField extends CreateRecord
{
    protected static string $resource = CustomFieldResource::class;
}
