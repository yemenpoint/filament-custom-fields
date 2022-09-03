<?php

namespace Yemenpoint\FilamentCustomFields\CustomFields;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;
use Yemenpoint\FilamentCustomFields\Models\CustomField;
use Yemenpoint\FilamentCustomFields\Models\CustomFieldResponse;

class FilamentCustomFieldsHelper
{

    public static function getTypes()
    {
        return [
            'number' => 'number',
            'text' => 'text',
            'select' => 'select',
            'textarea' => 'textarea',
            'rich_editor' => 'rich_editor',
            'toggle' => 'toggle',
        ];
    }


    public static function handle_custom_fields_request($data, $model, $id = null)
    {
        $custom_fields_data = [];
        foreach ($data as $key => $val) {
            if (Str::startsWith($key, 'customField_')) {
                $custom_fields_data[$key] = $val;
            }
        }


        foreach ($custom_fields_data as $custom_fields_datum => $value) {
            $keys = explode("_", $custom_fields_datum);

            $response = CustomFieldResponse::firstOrCreate(
                [
                    'model_id' => $id,
                    'model_type' => $model,
                    'field_id' => $keys[1],
                ],
                [
                    'model_id' => $id,
                    'model_type' => $model,
                    'field_id' => $keys[1],
                    'value' => $value,
                ]
            );
            $response->update(["value" => $value]);
        }

    }

    public static function custom_fields_column()
    {
        return TextColumn::make('customFieldResponses')->formatStateUsing(function ($record) {
            $dat = "";
            foreach ($record->customFieldResponses as $respons) {
                if ($respons->field->show_in_columns) {
                    $dat = $dat . $respons->field->title . "<br>" . $respons->value . "<br>";
                }
            }
            return new  \Illuminate\Support\HtmlString($dat);
        });
    }

    public static function custom_fields_form($model, $id = null)
    {
        if ($id) {
            $fields = CustomField::with(["responses" => function ($q) use ($id) {
                return $q->where("model_id", $id);
            }])->orderByDesc("order")->where("model_type", $model)->get();
        } else {
            $fields = CustomField::where("model_type", $model)->orderByDesc("order")->get();
        }

        if (!count($fields)) {
            return [];
        }

        $form = [];
        foreach ($fields as $field) {
            $default = $field->default_value;

            if ($id) {
                foreach ($field->responses as $response) {
                    if ($response->model_id == $id) {
                        $default = $response->value;
                    }
                }
            }


            if ($field->type == "select") {

                $input = Select::make("customField_" . $field->id)
                    ->label($field->title)
                    ->options($field->options)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }

            } elseif ($field->type == "textarea") {

                $input = Textarea::make("customField_" . $field->id)
                    ->label($field->title)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }

            } elseif ($field->type == "toggle") {

                $input = Toggle::make("customField_" . $field->id)
                    ->label($field->title)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

            } elseif ($field->type == "rich_editor") {

                $input = RichEditor::make("customField_" . $field->id)
                    ->label($field->title)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }

            } else {

                $input = TextInput::make("customField_" . $field->id)
                    ->label($field->title)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }
                if ($field->type == "number") {
                    $input->numeric();
                }
            }

            $form[] = $input;
        }
        return $form;
    }

}