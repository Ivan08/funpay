<?php

namespace FpDbTest\Specifier;

use FpDbTest\Lexeme\Lexeme;
use FpDbTest\Query\ParameterMapper;

abstract class AbstractSpecifier
{
    /**
     * @return string
     */
    abstract public function getMarker(): string;

    /**
     * @param Lexeme $lexeme
     * @param ParameterMapper $parameterMapper
     * @return void
     */
    public function run(Lexeme $lexeme, ParameterMapper $parameterMapper): void
    {
        $sql = $this->getSql($lexeme->arguments, $parameterMapper);
        $parameterMapper->lexeme($lexeme->getHash(), $sql);
    }

    /**
     * @param mixed $arguments
     * @param ParameterMapper $parameterMapper
     * @return string
     */
    abstract public function getSql(mixed $arguments, ParameterMapper $parameterMapper): string;
}
