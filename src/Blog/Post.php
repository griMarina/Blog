<?php

namespace Grimarina\Blog_Project\Blog;

class Post 
{
    private ?int $id;
    private ?int $id_user;
    private ?string $title;
    private ?string $text;


    public function __construct(int $id = null, int $id_user = null, string $title = null, string $text = null)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->title = $title;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return $this->title . " >>> " . $this->text . PHP_EOL;
    }
}