<?php

namespace Grimarina\Blog_Project\UnitTests\Container;

class ClassDependingOnAnother 
{
    public function __construct(
        private SomeClassWithoutDependencies $one,
        private SomeClassWithParameter $two,
    )
    {
    }
}