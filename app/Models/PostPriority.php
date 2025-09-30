<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostPriority extends Model
{
    protected $fillable = ['post_id', 'priority'];
}
