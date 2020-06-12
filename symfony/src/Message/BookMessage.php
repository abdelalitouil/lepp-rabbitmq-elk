<?php

namespace App\Message;

class BookMessage
{
    private $bookId;
    private $operation;

    public function __construct(int $bookId, string $operation)
    {
        $this->bookId = $bookId;
        $this->operation = $operation;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}