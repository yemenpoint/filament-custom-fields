<?php

namespace Yemenpoint\FilamentCustomFields\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms;
use Yemenpoint\FilamentCustomFields\Models\CustomFieldResponse;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResponseResource\Pages;

class CustomFieldResponseResource extends Resource
{
    protected static ?string $model = CustomFieldResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        TextInput::make("value")->columnSpan(2)
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->sortable(),
                Tables\Columns\TextColumn::make("field.title"),
                Tables\Columns\TextColumn::make("value"),
                Tables\Columns\TextColumn::make("model_type")->formatStateUsing(function ($state) {
                    $display = $state;
                    foreach (config("filament-custom-fields.models") as $key => $value) {
                        if ($key == $state) {
                            $display = $value;
                            break;
                        }
                    }
                    return $display;
                }),
                Tables\Columns\TextColumn::make("model_id"),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
}
