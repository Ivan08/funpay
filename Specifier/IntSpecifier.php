<?php

namespace FpDbTest\Specifier;

use FpDbTest\Query\ParameterMapper;

final class IntSpecifier extends AbstractSpecifier
{
    /**
     * @return string
     */
    public function getMarker(): string
    {
        return 'd';
    }

    /**
     * @param mixed $arguments
     * @param ParameterMapper $parameterMapper
     * @return string
     */
    public function getSql(mixed $arguments, ParameterMapper $parameterMapper): string
    {
        if (is_null($arguments)) {
            return $parameterMapper->setParam(null);
        }
        return $parameterMapper->setParam((int)$arguments);
    }
}
