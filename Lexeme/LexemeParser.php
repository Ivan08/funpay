<?php

namespace FpDbTest\Lexeme;

use FpDbTest\Exception\SqlParseException;
use FpDbTest\Query\Query;

readonly class LexemeParser
{
    /**
     * @param array $markers
     */
    public function __construct(private array $markers = [])
    {
    }

    /**
     * @param Query $query
     * @param array $arguments
     * @return array
     */
    public function get(Query $query, array $arguments): array
    {
        $count = preg_match_all(sprintf('/\?(%s)?/', implode('|', $this->markers)), $query->getSql(), $replaceMatches);
        if ($count != count($arguments)) {
            throw new SqlParseException('Invalid argument count');
        }
        $lexemes = [];
        for ($i = 0; $i < count($replaceMatches[1]); $i++) {
            $parameter = new Lexeme($replaceMatches[1][$i], $arguments[$i], $i);
            $hash = $parameter->getHash();
            $lexemes[$hash] = $parameter;
            $query->replaceFirst($replaceMatches[0][$i], $hash);
        }

        return $lexemes;
    }
}
