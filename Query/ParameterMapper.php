<?php

namespace FpDbTest\Query;

use mysqli;

class ParameterMapper
{
    private array $params = [];
    private array $lexeme = [];
    private array $field = [];

    /**
     * @param mixed $item
     * @param bool $isField
     * @return string
     */
    public function setParam(mixed $item, bool $isField = false): string
    {
        $slug = sprintf('m_%s_', count($this->params));
        $this->params[$slug] = $item;
        if ($isField) {
            $this->field[] = $slug;
        }
        return $slug;
    }

    /**
     * @param string $hash
     * @param string $sql
     * @return void
     */
    public function lexeme(string $hash, string $sql): void
    {
        $this->lexeme[$hash] = $sql;
    }

    /**
     * @param Query $query
     * @param mysqli $mysqli
     * @return void
     */
    public function prepare(Query $query, mysqli $mysqli): void
    {
        foreach ($this->lexeme as $hash => $sql) {
            $query->replace($hash, $sql);
        }
        foreach ($this->params as $name => $value) {
            $value = match (true) {
                is_null($value) => 'NULL',
                is_bool($value) => $value ? 1 : 0,
                is_string($value) => sprintf($this->stringSql($name), $mysqli->real_escape_string($value)),
                default => $value,
            };
            $query->replace($name, $value);
        }
    }

    /**
     * @param string $name
     * @return string
     */
    private function stringSql(string $name): string
    {
        if (in_array($name, $this->field)) {
            return '%s';
        }
        return "'%s'";
    }
}
