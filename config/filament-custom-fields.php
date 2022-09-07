<?php

use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResponseResource;

return [
    'resources' => [
        CustomFieldResource::class,
        CustomFieldResponseResource::class,
    ],
    'models' => [
//        \App\Models\Trying::class => "trying",
    ],
    "navigation_group" => "Custom Fields",
    "custom_fields_label" => "Custom Fields",
    "custom_field_responses_label" => "Custom Fields Responses",
];
