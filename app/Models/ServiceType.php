<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'charge_configuration' => 'json',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
