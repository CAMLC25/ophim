<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'season', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];
}
