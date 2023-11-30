<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServiceType;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceTypeResource\Pages;
use App\Filament\Resources\ServiceTypeResource\RelationManagers;

class ServiceTypeResource extends Resource
{
    protected static ?string $model = ServiceType::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autocapitalize()
                    ->autocomplete(),
                TextInput::make('charge_per_km')
                ->numeric()
                ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('total_services')
                    ->getStateUsing(function (ServiceType $serviceType) {
                        return $serviceType->services->count();
                    }),
                TextColumn::make('charge_per_km')
                ->money('ghs'),
                TextColumn::make('pending_services')
                    ->getStateUsing(function (ServiceType $serviceType) {
                        return $serviceType->services
                            ->where('status', 'pending')
                            ->count();
                    }),
                TextColumn::make('complete_services')
                    ->getStateUsing(function (ServiceType $serviceType) {
                        return $serviceType->services
                            ->where('status', 'complete')
                            ->count();
                    }),
                TextColumn::make('cancelled_services')
                    ->getStateUsing(function (ServiceType $serviceType) {
                        return $serviceType->services
                            ->where('status', 'cancelled')
                            ->count();
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListServiceTypes::route('/'),
            'create' => Pages\CreateServiceType::route('/create'),
            'edit' => Pages\EditServiceType::route('/{record}/edit'),
        ];
    }
}
