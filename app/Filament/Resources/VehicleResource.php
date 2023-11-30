<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('manufacturer'),
                TextInput::make('class'),
                TextInput::make('model'),
                DateTimePicker::make('registered_at'),
                Select::make('user_id')
                    ->options(User::role('driver')->pluck('name', 'id'))
                    ->searchable()
                    ->label('Driver'),
                TextInput::make('registration_number')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('manufacturer'),
                TextColumn::make('class'),
                TextColumn::make('model'),
                TextColumn::make('registration_number'),
                TextColumn::make('registered_at')->date(),
                TextColumn::make('user.name')
                    ->label('Driver'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('manufacturer'),
                TextEntry::make('class'),
                TextEntry::make('model'),
                TextEntry::make('registration_number'),
                TextEntry::make('registered_at')
                    ->date(),
                TextEntry::make('user.name')
                    ->label('Driver'),
                TextEntry::make('user.email')
                    ->label('Driver Email'),
                TextEntry::make('user.phone')
                    ->label('Driver Phone')
                    ->placeholder('N/A'),
                TextEntry::make('user.address')
                    ->placeholder('N/A')
                    ->label('Driver Address'),
                TextEntry::make('user.created_at')
                    ->placeholder('N/A')
                    ->label('Date Joined'),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
