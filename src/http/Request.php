<?php

namespace Grimarina\Blog_Project\http;

use Grimarina\Blog_Project\Exceptions\HttpException;

class Request 
{
    public function __construct(
        private array $get,
        private array $server
    )
    {    
    }

    // public function path(): string
    // {
    //     if (!array_key_exists('REQUEST_URI', $this->server)) {
    //         throw new HttpException ('Cannot get path from the request');
    //     }
    // }
}