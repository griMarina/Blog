<?php

namespace Grimarina\Blog_Project\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}