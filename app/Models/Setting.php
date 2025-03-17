<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_name',
        'logo_image',
        'description',
        'favicon_image',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
