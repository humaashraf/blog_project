<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'category_id', 'image', 'author_id', 'is_approved', 'published_by', 'post_id', 'reason', 'title', 'reason'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
    // public function author()
    // {
    //     return $this->belongsTo(User::class, 'author_id');
    // }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // PostReason Relationship
    // public function reason()
    // {
    //     return $this->hasOne(PostReason::class, 'post_id', 'id');
    // }
    // Relationship with PostReason
    public function reason()
    {
        return $this->hasMany(PostReason::class, 'post_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments(): HasMany
{
    return $this->hasMany(Comment::class);
}
protected $casts = [
    'published_at' => 'datetime',
];
public function visitors()
{
    return $this->hasMany(Visitor::class);
}

}
