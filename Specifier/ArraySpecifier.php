<?php

namespace FpDbTest\Specifier;

use FpDbTest\Exception\SqlParseException;
use FpDbTest\Query\ParameterMapper;

final class ArraySpecifier extends AbstractSpecifier
{
    /**
     * @return string
     */
    public function getMarker(): string
    {
        return 'a';
    }

    /**
     * @param mixed $arguments
     * @param ParameterMapper $parameterMapper
     * @return string
     */
    public function getSql(mixed $arguments, ParameterMapper $parameterMapper): string
    {
        if (!is_array($arguments)) {
            throw new SqlParseException('Is not array');
        }
        if (array_is_list($arguments)) {
            return implode(', ', array_map(fn($item) => $parameterMapper->setParam($item), $arguments));
        }
        $result = [];
        foreach ($arguments as $key => $value) {
            $result[] = sprintf('`%s` = %s', $parameterMapper->setParam($key, true), $parameterMapper->setParam($value));
        }

        return implode(', ', $result);
    }
}
