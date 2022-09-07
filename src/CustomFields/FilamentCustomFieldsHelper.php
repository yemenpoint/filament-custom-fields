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
use Filament\Forms;

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
        return TextColumn::make('responses')->formatStateUsing(function ($record) {
            $customFieldResponses = CustomFieldResponse::with("field")->where("model_type", get_class($record))->where("model_id", $record->id)->get();
            $dat = "";
            foreach ($customFieldResponses as $respons) {
                if ($respons->field->show_in_columns) {
                    $dat = $dat . $respons->field->title . "<br>" . $respons->value . "<br>";
                }
            }
            return new  \Illuminate\Support\HtmlString($dat);
        });
    }

    public static function custom_fields_form($model, $id = null): array
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
            $columnSpan = $field->column_span;

            if ($field->type == "select") {

                $input = Select::make("customField_" . $field->id)
                    ->label($field->title)
                    ->hint($field->hint)
                    ->options($field->options)
                    ->columnSpan($columnSpan)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }

            } elseif ($field->type == "textarea") {

                $input = Textarea::make("customField_" . $field->id)
                    ->label($field->title)
                    ->hint($field->hint)
                    ->columnSpan($columnSpan)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }

            } elseif ($field->type == "toggle") {

                $input = Toggle::make("customField_" . $field->id)
                    ->label($field->title)
                    ->hint($field->hint)
                    ->columnSpan($columnSpan)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

            } elseif ($field->type == "rich_editor") {

                $input = RichEditor::make("customField_" . $field->id)
                    ->label($field->title)
                    ->hint($field->hint)
                    ->columnSpan($columnSpan)
                    ->required($field->required == 1)
                    ->afterStateHydrated(fn($component) => $component->state($default))
                    ->default($default);

                if ($field->rules) {
                    $input->rules($field->rules);
                }

            } else {

                $input = TextInput::make("customField_" . $field->id)
                    ->label($field->title)
                    ->hint($field->hint)
                    ->columnSpan($columnSpan)
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
        if (count($form)) {
            return [
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema($form)
                ])
            ];
        }
        return [];
    }

}