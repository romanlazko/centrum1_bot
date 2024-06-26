<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function logs()
    {
        return $this->hasMany(AdvertisementLog::class);
    }
}
