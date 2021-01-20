<?php

namespace App\Domain\Tweet\Dto;

use App\Entity\Tweet;
use DateTimeImmutable;

class TweetDto
{
    public string $content;

    public function toEntity(): Tweet
    {
        return (new Tweet)
            ->setContent($this->content);
    }
}
