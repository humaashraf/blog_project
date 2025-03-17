<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'comment', 'is_approved', 'published_at'];
    protected $casts = [
        'published_at' => 'datetime',
    ];


    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
