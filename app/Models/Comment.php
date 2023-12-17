<?php

namespace App\Models;

use App\Events\CommentWritten;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Add this boot method here to fire the event
     */
    public static function boot()
    {
        parent::boot();
        static::created(function (Comment $comment) {
            CommentWritten::dispatch($comment);
        });
    }

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
