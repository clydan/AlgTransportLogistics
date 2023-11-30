<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Vehicle;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = [
            'name' => Arr::get($data, 'name'),
            'email' => Arr::get($data, 'email'),
            'password' => Hash::make(Arr::get($data, 'password')),
            'address' => Arr::get($data, 'address'),
            'phone' => Arr::get($data, 'phone'),
            'city' => Arr::get($data, 'city'),
            'state' => Arr::get($data, 'state'),
            'zip' => Arr::get($data, 'zip'),
            'is_driver_available' => Arr::get($data, 'user_type') == 'driver' ? true : false,
        ];
        $userRecord = static::getModel()::create($user);
        $userRecord->assignRole(Arr::get($data, 'user_type'));

        if (Arr::get($data, 'user_type') == 'driver') {
            $vehicleRecord = [
                'user_id' => $userRecord->id,
                'name' => Arr::get($data, 'vehicle_name'),
                'model' => Arr::get($data, 'vehicle_model'),
                'manufacturer' => Arr::get($data, 'vehicle_manufacturer'),
                'class' => Arr::get($data, 'vehicle_class'),
                'registered_at' => Arr::get($data, 'vehicle_registered_at'),
                'registration_number' => Arr::get($data, 'vehicle_registration_number'),
            ];
            $vehicleRecord = Vehicle::create($vehicleRecord);
        }

        $userRecord->refresh();

        return $userRecord;
    }
}
