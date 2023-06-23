<?php

namespace Yemenpoint\FilamentCustomFields\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Yemenpoint\FilamentCustomFields\CustomFields\PermissionsHelper;
use Yemenpoint\FilamentCustomFields\Models\CustomFieldResponse;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResponseResource\Pages;

class CustomFieldResponseResource extends Resource
{
    use PermissionsHelper;

    protected static ?string $resourceKey = 'custom_field_responses';

    protected static ?string $model = CustomFieldResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-custom-fields::resource.navigation_group');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-custom-fields::resource.custom_field_response_plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-custom-fields::resource.custom_field_response_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        TextInput::make("value")
                            ->label(__('filament-custom-fields::resource.custom_field_response.form.value.label'))
                            ->columnSpan(2)
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->searchable(isIndividual: true)
                    ->label(__('filament-custom-fields::resource.custom_field_response.table.id.label'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('field.title')
                    ->searchable(isIndividual: true)
                    ->label(__('filament-custom-fields::resource.custom_field_response.table.field_title.label')),
                Tables\Columns\TextColumn::make("value")
                    ->searchable(isIndividual: true)
                    ->label(__('filament-custom-fields::resource.custom_field_response.table.value.label')),
                Tables\Columns\TextColumn::make("model_type")
                    ->searchable(isIndividual: true)
                    ->label(__('filament-custom-fields::resource.custom_field_response.table.model_type.label'))
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
                Tables\Columns\TextColumn::make("model_id")
                    ->label(__('filament-custom-fields::resource.custom_field_response.table.model_id.label')),

            ])
            ->filters([
                //
            ])
            ->actions([
                ...static::getTableActions(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomFieldResponses::route('/'),
            'edit' => Pages\EditCustomFieldResponse::route('/{record}/edit'),
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
