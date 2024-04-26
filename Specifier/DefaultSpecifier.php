<?php

namespace FpDbTest\Specifier;

use FpDbTest\Exception\SqlParseException;
use FpDbTest\Query\ParameterMapper;

final class DefaultSpecifier extends AbstractSpecifier
{
    /**
     * @return string
     */
    public function getMarker(): string
    {
        return '';
    }

    /**
     * @param mixed $arguments
     * @param ParameterMapper $parameterMapper
     * @return string
     */
    public function getSql(mixed $arguments, ParameterMapper $parameterMapper): string
    {
        $availableType = [
            'boolean',
            'integer',
            'float',
            'string',
            'null',
        ];
        if (!in_array(gettype($arguments), $availableType)) {
            throw new SqlParseException('Invalid type');
        }
        return $parameterMapper->setParam($arguments);
    }
}
