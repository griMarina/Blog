<?php

namespace Grimarina\Blog_Project\Blog;

class User 
{
    private ?int $id;
    private ?string $firstname;
    private ?string $lastname;


    public function __construct(int $id = null, string $firstname = null, string $lastname = null)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function __toString(): string
    {
        return $this->firstname . " " . $this->lastname . PHP_EOL;
    }
}