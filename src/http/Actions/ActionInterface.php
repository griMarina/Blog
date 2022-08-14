<?php

namespace Grimarina\Blog_Project\http\Actions;

use Grimarina\Blog_Project\http\{Request, Response};

interface ActionInterface
{
    public function handle(Request $request): Response;
}