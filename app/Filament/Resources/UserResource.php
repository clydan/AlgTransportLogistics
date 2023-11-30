<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\DateTimePicker;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create User')
                    ->description('When you select the user type as Driver, 
                    fill the forms in the section below to create a vehicle for the user')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        TextInput::make('address'),
                        TextInput::make('city'),
                        TextInput::make('state'),
                        TextInput::make('zip'),
                        TextInput::make('country'),
                        Radio::make('user_type')
                            ->options([
                                'driver' => 'Driver',
                                'customer' => 'Customer',
                                'admin' => 'Adminstrator'
                            ]),
                    ])->columns(2),
                Section::make('Create Vehicle')
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->schema([
                        TextInput::make('vehicle_name'),
                        TextInput::make('vehicle_manufacturer'),
                        TextInput::make('vehicle_class'),
                        TextInput::make('vehicle_model'),
                        DateTimePicker::make('vehicle_registered_at'),
                        TextInput::make('vehicle_registration_number')
                    ])->columns(2),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('type')
                    ->getStateUsing(function (User $user) {
                        $roles = $user->getRoleNames()->toArray();
                        return implode(',', $roles);
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('services_count')
                    ->getStateUsing(function (User $user) {
                        $count = $user->services->count();
                        return $count == 0 ? 'Unavailable' : $count;
                    })
                    ->placeholder('heroicon-o-user-group'),
                TextColumn::make('created_at')->date()
                    ->label('joined'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
                TextEntry::make('email'),
                TextEntry::make('phone'),
                TextEntry::make('address'),
                TextEntry::make('city'),
                TextEntry::make('state'),
                TextEntry::make('zip'),
                TextEntry::make('country'),
                TextEntry::make('type')
                    ->getStateUsing(function (User $user) {
                        return implode(',', $user->getRoleNames()->toArray());
                    }),
                TextEntry::make('created_at')->date()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
