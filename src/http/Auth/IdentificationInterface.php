<?php

namespace Grimarina\Blog_Project\http\Auth;

use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\http\Request;

interface IdentificationInterface 
{
    public function user(Request $request): User;
}