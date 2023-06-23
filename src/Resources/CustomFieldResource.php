<?php

namespace Yemenpoint\FilamentCustomFields\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Yemenpoint\FilamentCustomFields\Models\CustomField;
use Yemenpoint\FilamentCustomFields\CustomFields\PermissionsHelper;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource\Pages;
use Yemenpoint\FilamentCustomFields\CustomFields\FilamentCustomFieldsHelper;

class CustomFieldResource extends Resource
{
    use PermissionsHelper;

    protected static ?string $resourceKey = 'custom_fields';

    protected static array $options = [];

    protected static ?string $model = CustomField::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-custom-fields::resource.navigation_group');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-custom-fields::resource.custom_field_plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-custom-fields::resource.custom_field_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('model_type')
                            ->label(__('filament-custom-fields::resource.custom_field.form.model_type.label'))
                            ->options(config("filament-custom-fields.models"))
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label(__('filament-custom-fields::resource.custom_field.form.type.label'))
                            ->reactive()
                            ->options(FilamentCustomFieldsHelper::getTypes())->default("text")
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-custom-fields::resource.custom_field.form.title.label'))
                            ->required(),
                        Forms\Components\TextInput::make('hint')
                            ->label(__('filament-custom-fields::resource.custom_field.form.hint.label')),

                        \Filament\Forms\Components\Fieldset::make('options_fieldset')
                            ->label(__('filament-custom-fields::resource.custom_field.form.select.options_fieldset.label'))
                            ->schema([
                                Forms\Components\Repeater::make('options')
                                    ->disableLabel()
                                    ->before(function (?Model $record) {
                                        static::$options = (array) ($record?->options ?? []);
                                    })
                                    ->columnSpan("full")
                                    ->hidden(fn (callable $get) => $get("type") != "select")
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Hidden::make('updatedAt')
                                                    ->disabled()
                                                    ->dehydrateStateUsing(fn () => now()->format('c')),

                                                Forms\Components\TextInput::make('label')
                                                    ->label(__('filament-custom-fields::resource.custom_field.form.select.label.label'))
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(
                                                        function (
                                                            ?string $state,
                                                            callable $set,
                                                            callable $get,
                                                            string $context,
                                                            ?Model $record,
                                                        ) {
                                                            if (($context != 'create') && $get('updatedAt')) {
                                                                return;
                                                            }

                                                            $count = collect($record?->options)
                                                                ->where('value', $state)->count();

                                                            $set('value', str($state)->slug()->append(
                                                                $count ? '_' . ($count + 1) : ''
                                                            ));
                                                        }
                                                    )
                                                    ->columnSpan(1),

                                                Forms\Components\TextInput::make('value')
                                                    ->label(__('filament-custom-fields::resource.custom_field.form.select.value.label'))
                                                    ->string()
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(
                                                        function (
                                                            ?string $state,
                                                            ?string $old,
                                                            callable $get,
                                                            callable $set,
                                                            ?Model $record,
                                                        ) {
                                                            $state = trim((string) $state);

                                                            if ($get('updatedAt') || !($state && collect($record?->options)
                                                                ->where('value', $state)->count())) {
                                                                return;
                                                            }

                                                            Notification::make()
                                                                ->danger()
                                                                ->title(
                                                                    __('filament-custom-fields::resource.custom_field.form.select.value.validation.duplicated.title')
                                                                )
                                                                ->body(
                                                                    __('filament-custom-fields::resource.custom_field.form.select.value.validation.duplicated.body')
                                                                )
                                                                ->seconds(3)
                                                                ->id('select.value.validation.duplicated') // prevent duplication
                                                                ->send();

                                                            $set('value', $old ?: '');
                                                        }
                                                    )
                                                    ->disabled(function (?string $state, callable $get, ?Model $record) {
                                                        return $state && collect($record?->options)
                                                            ->where('value', $state)->count() && $get('updatedAt');
                                                    })
                                                    ->columnSpan(1),
                                            ]),
                                    ])
                                    ->grid(1),
                            ])
                            ->hidden(fn (callable $get) => $get("type") != "select"),

                        Grid::make(4)
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Forms\Components\Toggle::make('required')
                                            ->label(__('filament-custom-fields::resource.custom_field.form.required.label'))
                                            ->columnSpan(1)->default(true),
                                        Forms\Components\Toggle::make('show_in_columns')
                                            ->label(__('filament-custom-fields::resource.custom_field.form.show_in_columns.label'))
                                            ->columnSpan(1)->default(true),
                                    ]),
                                Forms\Components\TextInput::make('default_value')
                                    ->label(__('filament-custom-fields::resource.custom_field.form.default_value.label')),
                                Forms\Components\TextInput::make('column_span')
                                    ->label(__('filament-custom-fields::resource.custom_field.form.column_span.label'))
                                    ->numeric()->maxValue(12)->minValue(1)->default(1),
                                Forms\Components\TextInput::make('order')
                                    ->label(__('filament-custom-fields::resource.custom_field.form.order.label'))
                                    ->numeric()->default(1),
                                Forms\Components\TextInput::make('rules')
                                    ->label(__('filament-custom-fields::resource.custom_field.form.rules.label')),
                            ]),
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->label(__('filament-custom-fields::resource.custom_field.table.id.label'))
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make("order")
                    ->label(__('filament-custom-fields::resource.custom_field.table.order.label'))
                    ->sortable(),

                Tables\Columns\TextColumn::make("title")
                    ->label(__('filament-custom-fields::resource.custom_field.table.title.label'))
                    ->searchable(isIndividual: true),

                Tables\Columns\TextColumn::make("type")
                    ->label(__('filament-custom-fields::resource.custom_field.table.type.label')),

                Tables\Columns\TextColumn::make("model_type")
                    ->label(__('filament-custom-fields::resource.custom_field.table.model_type.label'))
                    ->searchable(isIndividual: true)
                    ->formatStateUsing(function ($state) {
                        $display = $state;
                        foreach (config("filament-custom-fields.models") as $key => $value) {
                            if ($key == $state) {
                                $display = $value;
                                break;
                            }
                        }
                        return $display;
                    }),

                Tables\Columns\TextColumn::make("rules")
                    ->label(__('filament-custom-fields::resource.custom_field.table.rules.label')),
                Tables\Columns\IconColumn::make("required")
                    ->label(__('filament-custom-fields::resource.custom_field.table.required.label'))

                    ->boolean(),
                Tables\Columns\IconColumn::make("show_in_columns")
                    ->label(__('filament-custom-fields::resource.custom_field.table.show_in_columns.label'))
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\Filter::make('type_number')
                    ->label(__('filament-custom-fields::resource.custom_field.filters.type_number_label'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'number')),

                Tables\Filters\Filter::make('type_text')
                    ->label(__('filament-custom-fields::resource.custom_field.filters.type_text_label'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'text')),

                Tables\Filters\Filter::make('type_select')
                    ->label(__('filament-custom-fields::resource.custom_field.filters.type_select_label'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'select')),

                Tables\Filters\Filter::make('type_textarea')
                    ->label(__('filament-custom-fields::resource.custom_field.filters.type_textarea_label'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'textarea')),

                Tables\Filters\Filter::make('type_rich_editor')
                    ->label(__('filament-custom-fields::resource.custom_field.filters.type_rich_editor_label'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'rich_editor')),

                Tables\Filters\Filter::make('type_toggle')
                    ->label(__('filament-custom-fields::resource.custom_field.filters.type_toggle_label'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'toggle')),

            ])
            ->actions([
                ...static::getTableActions(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomFields::route('/'),
            'create' => Pages\CreateCustomField::route('/create'),
            'edit' => Pages\EditCustomField::route('/{record}/edit'),
        ];
    }

    /**
     * resourceKey function
     *
     * @return ?string
     */
    public static function resourceKey(): ?string
    {
        return static::$resourceKey ?? null;
    }
}
