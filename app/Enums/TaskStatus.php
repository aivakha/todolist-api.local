<?php

namespace App\Enums;

enum TaskStatus: int
{
    case TODO = 0;
    case DONE = 1;

    public function isTodo(): bool
    {
        return $this === self::TODO;
    }

    public function isDone(): bool
    {
        return $this === self::DONE;
    }

    public function getLabelText(): string
    {
        return match($this) {
            self::TODO => 'Todo',
            self::DONE => 'Done'
        };
    }
}
