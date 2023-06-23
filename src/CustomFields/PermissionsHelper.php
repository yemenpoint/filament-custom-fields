<?php

namespace Yemenpoint\FilamentCustomFields\CustomFields;

use Illuminate\Database\Eloquent\Model;

trait PermissionsHelper
{
    /**
     * resourceKey function
     *
     * @return ?string
     */
    abstract public static function resourceKey(): ?string;

    /**
     * canCheck function
     *
     * @param string $resourceKey
     * @param string $action
     * @param Model|null $record
     * @return boolean
     */
    public static function canCheck(string $resourceKey, string $action, ?Model $record = null): bool
    {
        if (!$resourceKey || !$action) {
            return false;
        }

        $prefix = "filament-custom-fields.resources_actions.{$resourceKey}.{$action}";

        if (!config("{$prefix}.enabled")) {
            return false;
        }

        if (!config("{$prefix}.validate_permission")) {
            return true;
        }

        if (config("{$prefix}.can") === null) {
            return true;
        }

        if (!is_string(config("{$prefix}.can"))) {
            return false;
        }

        $currentSet = static::shouldAuthorizeWithGate(); // Copy settings

        static::authorizeWithGate(true); // Force validate with gate

        $can = static::can(config("{$prefix}.can"), $record);

        static::authorizeWithGate($currentSet); // Restore before settings

        return (bool) $can;
    }

    public static function canView(Model $record): bool
    {
        return static::canCheck(static::resourceKey() ?? '', 'view', $record);
    }

    public static function canViewAny(): bool
    {
        return static::canCheck(static::resourceKey() ?? '', 'view');
    }

    public static function canCreate(): bool
    {
        return static::canCheck(static::resourceKey() ?? '', 'create');
    }

    public static function canEdit(Model $record): bool
    {
        return static::canCheck(static::resourceKey() ?? '', 'edit', $record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canCheck(static::resourceKey() ?? '', 'delete', $record);
    }
}
