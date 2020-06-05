<?php

namespace App\Message;

class BookMessage
{
    private $bookId;

    public function __construct(int $bookId)
    {
        $this->bookId = $bookId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }
}