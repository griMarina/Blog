<?php

namespace Grimarina\Blog;

class User 
{
    private ?int $id;
    private ?string $name;

    public function __construct(int $id = null, string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name . PHP_EOL;
    }
}