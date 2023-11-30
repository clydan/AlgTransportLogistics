<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'route' => 'json'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
