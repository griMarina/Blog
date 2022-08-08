<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Blog\Exceptions\ArgumentsException;

final class Arguments 
{
    private array $arguments = [];

    public function __construct(iterable $arguments)
    {
        foreach ($arguments as $argument => $value) {
            $stringValue = trim((string)$value);
            
            if (empty($stringValue)) {
                continue;
            }

            $this->arguments[(string)$argument] = $stringValue;
        }
    }

    public static function fromArgv(array $argv): self
    {
        $arguments = [];

        foreach ($argv as $argument) {
            //var_dump($argv);
            $parts = explode('=', $argument);
            //var_dump($parts);

            if (count($parts) !== 2) {
                continue;
            }
            $arguments[$parts[0]] = $parts[1];
        }

        //print_r($arguments);


        die;

        return new self($arguments);
    }

    public function get(string $argument): string
    {
        if (!array_key_exists($argument, $this->arguments)) {
            throw new ArgumentsException (
                "No such argument: $argument"
            );
        }

        return $this->arguments[$argument];
    }
}