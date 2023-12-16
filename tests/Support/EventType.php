<?php

namespace Tests\Support;

enum EventType: string
{
    case lessonWatched = 'lessonWatched';
    case CommentWritten = 'CommentWritten';
}
