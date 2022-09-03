<?php

namespace Yemenpoint\FilamentCustomFields;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;

class FilamentCustomFieldsServiceProvider extends PackageServiceProvider
{

    protected array $resources = [
        CustomFieldResource::class
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-custom-fields')
            ->hasConfigFile()
            ->hasMigration("create_custom_fields_tables");
    }
}
