<?php

namespace Yemenpoint\FilamentCustomFields;

use Spatie\LaravelPackageTools\Package;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;
use Filament\PluginServiceProvider;

class FilamentCustomFieldsServiceProvider extends PluginServiceProvider
{

    protected function getResources(): array
    {
        return config("filament-custom-fields.resources", []);
    }


    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-custom-fields')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration("create_custom_fields_tables");
    }
}
