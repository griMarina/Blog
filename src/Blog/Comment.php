<?php

namespace Grimarina\Blog_Project\Blog;

class Comment 
{
    private ?int $id;
    private ?int $id_user;
    private ?int $id_post;
    private ?string $text;


    public function __construct(int $id = null, int $id_user = null, int $id_post = null, string $text = null)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_post = $id_post;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return $this->text . PHP_EOL;
    }
}
