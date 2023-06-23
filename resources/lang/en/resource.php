<?php

return [
    'navigation_group' => 'Custom Fields',

    'custom_field_label' => 'Custom Fields',
    'custom_field_plural_label' => 'Custom Fields',

    'custom_field_response_label' => 'Custom Fields Responses',
    'custom_field_response_plural_label' => 'Custom Fields Responses',

    'custom_field' => [
        'form' => [
            'select' => [
                'options_fieldset' => [
                    'label' => 'Options',
                ],
                'label' => [
                    'label' => 'Label',
                    'help_text' => '',
                ],
                'value' => [
                    'label' => 'Value',
                    'help_text' => '',
                    'validation' => [
                        'duplicated' => [
                            'title' => 'Duplicated value',
                            'body' => 'Alread exists an value with this value',
                        ]
                    ]
                ],
            ],
            'id' => [
                'label' => 'ID'
            ],
            'order' => [
                'label' => 'Ordem'
            ],
            'title' => [
                'label' => 'Title'
            ],
            'type' => [
                'label' => 'Type'
            ],
            'model_type' => [
                'label' => 'Model type'
            ],
            'rules' => [
                'label' => 'Rules'
            ],
            'required' => [
                'label' => 'Required'
            ],
            'show_in_columns' => [
                'label' => 'Show in columns'
            ],
            'hint' => [
                'label' => 'Hint'
            ],
            'default_value' => [
                'label' => 'Default value'
            ],
            'column_span' => [
                'label' => 'Column span (width)'
            ],
        ],

        'table' => [
            'id' => [
                'label' => 'ID'
            ],
            'order' => [
                'label' => 'Ordem'
            ],
            'title' => [
                'label' => 'Title'
            ],
            'type' => [
                'label' => 'Type'
            ],
            'model_type' => [
                'label' => 'Model type'
            ],
            'rules' => [
                'label' => 'Rules'
            ],
            'required' => [
                'label' => 'Required'
            ],
            'show_in_columns' => [
                'label' => 'Show in columns'
            ],
        ],

        'filters' => [
            'type_number_label' => 'Type number',
            'type_text_label' => 'Type text',
            'type_select_label' => 'Type select',
            'type_textarea_label' => 'Type textarea',
            'type_rich_editor_label' => 'Type rich editor',
            'type_toggle_label' => 'Type toggle',
        ],
    ],

    'custom_field_response' => [
        'table' => [
            'id' => [
                'label' => 'Id',
            ],
            'field_title' => [
                'label' => 'Field title',
            ],
            'value' => [
                'label' => 'Value',
            ],
            'model_type' => [
                'label' => 'Model type',
            ],
            'model_id' => [
                'label' => 'Model ID',
            ],
        ],
        'form' => [
            'value' => [
                'label' => 'Value',
            ],
        ],
    ],
];
