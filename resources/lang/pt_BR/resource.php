<?php

return [
    'form' => [
        'select' => [
            'options_fieldset' => [
                'label' => 'Opções',
            ],
            'label' => [
                'label' => 'Etiqueta',
                'help_text' => 'Etiqueta do valor',
            ],
            'value' => [
                'label' => 'Valor',
                'help_text' => 'Valor armazenado',
                'validation' => [
                    'duplicated' => [
                        'title' => 'Valor duplicado',
                        'body' => 'Já existe uma opção com esse valor',
                    ]
                ]
            ],
        ]
    ],
];
