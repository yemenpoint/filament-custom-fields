<?php

return [
    'form' => [
        'select' => [
            'options_fieldset' => [
                'label' => 'Options',
            ],
            'label' => [
                'label' => 'Label',
                'help_text' => 'Label of value',
            ],
            'value' => [
                'label' => 'Value',
                'help_text' => 'Stored value',
                'validation' => [
                    'duplicated' => [
                        'title' => 'Duplicated value',
                        'body' => 'An option with that value already exists',
                    ]
                ]
            ],
        ]
    ],
];
