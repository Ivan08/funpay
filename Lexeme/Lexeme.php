<?php

namespace FpDbTest\Lexeme;

readonly class Lexeme
{
    /**
     * @param string $specifier
     * @param mixed $arguments
     * @param int $number
     */
    public function __construct(public string $specifier, public mixed $arguments, private int $number)
    {
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return sha1($this->specifier . serialize($this->arguments) . $this->number);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function contains(mixed $value): bool
    {
        $skip = $value;
        if (!is_array($skip)) {
            $skip = [$skip];
        }
        $arguments = [];
        if (is_array($this->arguments)) {
            if (array_is_list($this->arguments)) {
                $arguments = array_merge($arguments, array_keys($this->arguments));
            } else {
                $arguments = array_merge($arguments, $this->arguments);
            }
        } else {
            $arguments[] = $this->arguments;
        }

        return (bool)array_intersect($skip, $arguments);
    }
}
