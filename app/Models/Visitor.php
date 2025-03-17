<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'visitor_ip', 'visited_at', 'page_name'];

    public $timestamps = true; // visited_at already timestamped

}
