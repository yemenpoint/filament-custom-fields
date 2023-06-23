<?php

namespace Yemenpoint\FilamentCustomFields;

use Closure;
use Spatie\LaravelPackageTools\Package;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;
use Filament\PluginServiceProvider;

class FilamentCustomFieldsServiceProvider extends PluginServiceProvider
{
    protected static ?string $packageName = 'filament-custom-fields';

    public static function getPackageName(string ...$toMerge): string
    {
        $packageName = static::$packageName ?? str(__CLASS__)
            ->classBasename()
            ->kebab()
            ->replace('-service-provider', '')
            ->toString();

        return implode('.', [
            $packageName,
            ...$toMerge,
        ]);
    }

    protected function getResources(): array
    {
        return config(static::getPackageName('resources'), []);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::getPackageName())
            ->hasConfigFile([
                static::getPackageName(),
            ])
            ->hasTranslations()
            ->hasMigration('create_custom_fields_tables');
    }
}
