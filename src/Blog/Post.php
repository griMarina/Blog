<?php

namespace Grimarina\Blog_Project\Blog;

use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Blog\User;

class Post 
{
    public function __construct(
        private UUID $uuid, 
        private UUID $author_uuid, 
        private string $title, 
        private string $text
    )
    {
    }

    public function __toString(): string
    {
        return $this->title . " >>> " . $this->text . PHP_EOL;
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }
    
    public function getAuthor_uuid(): UUID
    {
        return $this->author_uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
       return $this->text;
    }
}