<?php

namespace FpDbTest\Specifier;

use FpDbTest\Exception\SqlParseException;
use FpDbTest\Query\ParameterMapper;

final class IdentSpecifier extends AbstractSpecifier
{
    /**
     * @return string
     */
    public function getMarker(): string
    {
        return '#';
    }

    /**
     * @param mixed $arguments
     * @param ParameterMapper $parameterMapper
     * @return string
     */
    public function getSql(mixed $arguments, ParameterMapper $parameterMapper): string
    {
        if (!is_array($arguments)) {
            $arguments = [$arguments];
        }
        if (!array_is_list($arguments)) {
            throw new SqlParseException('Is not associative array');
        }

        $arguments = array_map(fn($item) => sprintf('`%s`', $parameterMapper->setParam($item, true)), $arguments);
        return implode(', ', $arguments);
    }
}
