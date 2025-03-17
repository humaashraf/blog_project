<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReason extends Model
{
    use HasFactory;
    protected $table = 'post_reason';
    protected $fillable = ['post_id', 'reason'];

    // Relationship with Post
    // public function post()
    // {
    //     return $this->belongsTo(Post::class, 'post_id', 'id');
    // }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }


}
