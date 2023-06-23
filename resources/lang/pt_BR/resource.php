<?php

return [
    'navigation_group' => 'Campos adicionais',

    'custom_field_label' => 'Campo adicional',
    'custom_field_plural_label' => 'Campos adicionais',

    'custom_field_response_label' => 'Resposta de campo adicional',
    'custom_field_response_plural_label' => 'Respostas de campos adicionais',

    'custom_field' => [
        'form' => [
            'select' => [
                'options_fieldset' => [
                    'label' => 'Opções',
                ],
                'label' => [
                    'label' => 'Opção',
                    'help_text' => '',
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
            ],
            'id' => [
                'label' => 'ID'
            ],
            'order' => [
                'label' => 'Ordem'
            ],
            'title' => [
                'label' => 'Título'
            ],
            'type' => [
                'label' => 'Tipo'
            ],
            'model_type' => [
                'label' => 'Model'
            ],
            'rules' => [
                'label' => 'Regras'
            ],
            'required' => [
                'label' => 'Obrigatório'
            ],
            'show_in_columns' => [
                'label' => 'Mostrar nas colunas'
            ],
            'hint' => [
                'label' => 'Texto de ajuda'
            ],
            'default_value' => [
                'label' => 'Valor padrão'
            ],
            'column_span' => [
                'label' => 'Largura'
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
                'label' => 'Título'
            ],
            'type' => [
                'label' => 'Tipo'
            ],
            'model_type' => [
                'label' => 'Model'
            ],
            'rules' => [
                'label' => 'Regras'
            ],
            'required' => [
                'label' => 'Obrigatório'
            ],
            'show_in_columns' => [
                'label' => 'Mostrar nas colunas'
            ],
        ],

        'filters' => [
            'type_number_label' => 'Tipo number',
            'type_text_label' => 'Tipo text',
            'type_select_label' => 'Tipo select',
            'type_textarea_label' => 'Tipo textarea',
            'type_rich_editor_label' => 'Tipo rich_editor',
            'type_toggle_label' => 'Tipo toggle',
        ],
    ],

    'custom_field_response' => [
        'table' => [
            'id' => [
                'label' => 'ID',
            ],
            'field_title' => [
                'label' => 'Campo',
            ],
            'value' => [
                'label' => 'Valor',
            ],
            'model_type' => [
                'label' => 'Model',
            ],
            'model_id' => [
                'label' => 'ID na model',
            ],
        ],
        'form' => [
            'value' => [
                'label' => 'Valor',
            ],
        ],
    ],
];
