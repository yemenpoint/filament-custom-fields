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

    /**
     * @deprecated Use `handleCustomFieldsRequest()` method instead.
     */
    public static function handle_custom_fields_request($data, $model, $id = null)
    {
        return static::handleCustomFieldsRequest($data, $model, $id);
    }

    /**
     * handleCustomFieldsRequest function
     *
     * @param ?array $data
     * @param ?string $model
     * @param mixed $id
     *
     * @return void
     */
    public static function handleCustomFieldsRequest($data, $model, $id = null)
    {
        if (!$data || !$model) {
            return;
        }

        $customFieldsData = [];
        foreach ($data as $key => $val) {
            if (Str::startsWith($key, 'customField_')) {
                $customFieldsData[$key] = $val;
            }
        }

        foreach ($customFieldsData as $custom_fields_datum => $value) {
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

    /**
     * @deprecated Use `customFieldsColumn()` method instead.
     */
    public static function custom_fields_column()
    {
        return static::customFieldsColumn();
    }

    /**
     * customFieldsColumn function
     *
     * @return \Filament\Tables\Columns\TextColumn
     */
    public static function customFieldsColumn()
    {
        return TextColumn::make('responses')
            ->formatStateUsing(function ($record) {
                $customFieldResponses = CustomFieldResponse::with("field")
                    ->where("model_type", get_class($record))
                    ->where("model_id", $record->id)
                    ->get();

                $data = "";
                foreach ($customFieldResponses as $respons) {
                    if (!$respons->field->show_in_columns) {
                        continue;
                    }

                    $data .= "{$respons->field->title} <br> {$respons->value} <br>";
                }

                return new  \Illuminate\Support\HtmlString($data);
            });
    }

    /**
     * @deprecated Use `customFieldsForm()` method instead.
     */
    public static function custom_fields_form($model, $id = null): array
    {
        return static::customFieldsForm($model, $id);
    }

    public static function customFieldsForm($model, $id = null): array
    {
        $form = [];

        $query = CustomField::query();

        if ($id) {
            $query = $query->with(["responses" => function ($q) use ($id) {
                    return $q->where("model_id", $id);
                }]);
        }

        $form = [];

        $query->where("model_type", $model)
            ->orderByDesc("order")
            ->chunk(100, function ($fields) use ($id, &$form) {
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
                        $options = collect($field->options)
                            ->filter(fn ($item) => collect($item)->has(['value', 'label']))
                            ->flatMap(fn ($item) => [$item['value'] => $item['label']]);

                        $input = Select::make("customField_" . $field->id)
                            ->label($field->title)
                            ->hint($field->hint)
                            ->options($options->toArray())
                            ->columnSpan($columnSpan)
                            ->required($field->required == 1)
                            ->afterStateHydrated(fn ($component) => $component->state($default))
                            ->searchable((bool) config('filament-custom-fields.type_variations.select.searchable'))
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
                            ->afterStateHydrated(fn ($component) => $component->state($default))
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
                            ->afterStateHydrated(fn ($component) => $component->state($default))
                            ->default($default);
                    } elseif ($field->type == "rich_editor") {

                        $input = RichEditor::make("customField_" . $field->id)
                            ->label($field->title)
                            ->hint($field->hint)
                            ->columnSpan($columnSpan)
                            ->required($field->required == 1)
                            ->afterStateHydrated(fn ($component) => $component->state($default))
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
                            ->afterStateHydrated(fn ($component) => $component->state($default))
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
            });

        return $form ? [
            Forms\Components\Card::make()->schema([
                Forms\Components\Grid::make()->schema($form)
            ])
        ] : [];
    }
}
