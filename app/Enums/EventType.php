<?php

namespace App\Enums;

enum EventType: string
{
    case lessonWatched = 'lessonWatched';
    case CommentWritten = 'CommentWritten';
}
