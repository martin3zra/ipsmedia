<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    /**
     * The users that has access to the lesson.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function markLessonWasWatchedBy(User $user): void
    {
        $this->users()->attach([$user->id => [
            'watched' => true,
        ]]);
    }
}
