<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListingResource\Pages;
use App\Filament\Resources\ListingResource\RelationManagers;
use App\Models\Listing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),


                Forms\Components\Textarea::make('description')
                    ->required()
                    ->placeholder('Enter the description of the listing'),


                Forms\Components\TextInput::make('address')
                    ->required()
                    ->placeholder('Enter the address of the listing'),


                Forms\Components\TextInput::make('sqft')
                    ->required()
                    ->placeholder('Enter the square feet of the listing'),


                Forms\Components\TextInput::make('wifi_speed')
                    ->label('Wifi Speed')
                    ->required()
                    ->placeholder('Enter the wifi speed of the listing'),


                Forms\Components\TextInput::make('max_person')
                    ->label('Max Person')
                    ->required()
                    ->placeholder('Enter the max person of the listing'),


                Forms\Components\TextInput::make('price_per_day')
                    ->label('Price Per Day')
                    ->required()
                    ->placeholder('Enter the price per day of the listing'),

                Forms\Components\Checkbox::make('full_support_available')
                    ->label('Full Support Available')
                    ->default(0),

                Forms\Components\Checkbox::make('gym_area_available')
                    ->label('Gym Area Available')
                    ->default(0),

                Forms\Components\Checkbox::make('mini_cafe_available')
                    ->label('Mini Cafe Available')
                    ->default(0),

                Forms\Components\Checkbox::make('cinema_available')
                    ->label('Cinema Available')
                    ->default(0),

                Forms\Components\FileUpload::make('attachments')
                    ->columnSpanFull()
                    ->directory('listings')
                    ->image()
                    ->openable()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('sqft')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wifi_speed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_person')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_day')
                    ->money('USD')
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListings::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit' => Pages\EditListing::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}