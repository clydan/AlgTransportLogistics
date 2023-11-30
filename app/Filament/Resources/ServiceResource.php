<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ServiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceResource\RelationManagers;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_number'),
                TextColumn::make('serviceType.name')
                    ->searchable(),
                TextColumn::make('vehicle.name')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('vehicle.user.name')
                    ->label('Driver')->searchable(),
                TextColumn::make('duration_in_hours'),
                TextColumn::make('estimated_cost')->money('ghs'),
                SelectColumn::make('status')
                    ->options([
                        'pending' => 'pending',
                        'completed' => 'completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('service status'),
                TextColumn::make('created_at')->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('serviceType')
                    ->relationship('serviceType', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
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
                TextEntry::make('serviceType.name'),
                TextEntry::make('tracking_number'),
                TextEntry::make('Customer_name')
                ->getStateUsing(fn (Service $record) => $record->user->name),
                TextEntry::make('Driver_name')
                ->getStateUsing(fn (Service $record) => $record->vehicle->user->name),
                TextEntry::make('vehicle')
                ->getStateUsing(fn (Service $record) => $record->vehicle->name),
                TextEntry::make('duration_in_hours'),
                TextEntry::make('journey_starts_at')
                ->getStateUsing(fn(Service $record) => $record->route?->start_location),
                TextEntry::make('journey_ends_at')
                ->getStateUsing(fn(Service $record) => $record->route?->end_location),
                TextEntry::make('estimated_cost')
                ->money('ghs'),
                TextEntry::make('status'),
                TextEntry::make('description'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
