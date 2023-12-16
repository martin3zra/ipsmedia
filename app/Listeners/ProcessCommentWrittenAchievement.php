<?php

namespace App\Listeners;

use App\Actions\CommentAchievement;
use App\Events\CommentWritten;

class ProcessCommentWrittenAchievement
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {

        $commentAchievement = new CommentAchievement(
            comment: $event->comment,
        );

        $commentAchievement->execute();
    }
}
