<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)
            ->wherePivot('watched', true)
            ->withTimestamps();
    }

    /**
     * The lessons that a user has watched.
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class)
            ->withTimestamps();
    }

    /**
     * The achievements based on comment he left.
     */
    public function commentAchievements()
    {
        return $this->belongsToMany(Achievement::class)
            ->wherePivot('type', EventType::commentWritten->value)
            ->withTimestamps();
    }

    /**
     * The achievements based on lesson watched
     */
    public function watchedLessonAchievements()
    {
        return $this->belongsToMany(Achievement::class)
            ->wherePivot('type', EventType::lessonWatched->value)
            ->withTimestamps();
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class)
            ->wherePivot('user_id', $this->id)
            ->withTimestamps();
    }

    public function latestBadge()
    {
        $badge = $this->belongsToMany(Badge::class)
            ->orderBy('id', 'desc')
            ->first();

        if ($badge === null) {
            return Badge::first();
        }

        return $badge;
    }
}

