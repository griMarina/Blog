<?php

namespace Grimarina\Blog_Project\http\Actions;

use Grimarina\Blog_Project\http\Request;
use Grimarina\Blog_Project\http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}