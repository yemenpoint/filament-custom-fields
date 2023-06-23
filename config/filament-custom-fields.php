<?php

use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResponseResource;

return [
    'resources' => [
        CustomFieldResource::class,
        CustomFieldResponseResource::class,
    ],

    'models' => [
        // \App\Models\Trying::class => "trying",
    ],

    'type_variations' => [
        'number' => [
            //
        ],
        'text' => [
            //
        ],
        'select' => [
            /**
             * Enable search for all custom inputs as 'select'
             */
            'searchable' => true,
        ],
        'textarea' => [
            //
        ],
        'rich_editor' => [
            //
        ],
        'toggle' => [
            //
        ],
    ],

    'resources_actions' => [
        /**
         * [BETA]
         *
         * Explain:
         * filament-custom-fields.custom_field_responses.*
         * .enabled = show option
         * .validate_permission = if use gate permissions
         * .can = validate permition if can do action... (if is null = anyone can see this action)
         */
        'custom_field_responses' => [
            'edit' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'edit-custom-field-response',
            ],
            'view' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'view-custom-field-response',
            ],
            'delete' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'delete-custom-field-response',
            ],
            // 'create' => [
            //     'enabled' => false,
            //     'validate_permission' => true,
            //     'can' => 'create-custom-field-response',
            // ],
        ],

        'custom_fields' => [
            'edit' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'edit-custom-field',
            ],
            'view' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'view-custom-field',
            ],
            'delete' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'delete-custom-field',
            ],
            'create' => [
                'enabled' => true,
                'validate_permission' => false,
                'can' => 'create-custom-field',
            ],
        ],
    ],
];
