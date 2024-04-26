<?php

namespace FpDbTest;

use FpDbTest\Exception\SqlParseException;
use FpDbTest\Lexeme\Lexeme;
use FpDbTest\Lexeme\LexemeParser;
use FpDbTest\Query\ParameterMapper;
use FpDbTest\Query\Query;
use FpDbTest\Specifier\SpecifierCollection;
use mysqli;

class Database implements DatabaseInterface
{
    private const bool ALLOW_SUB_CONDITION = false;
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function buildQuery(string $query, array $arguments = []): string
    {
        $queryObject = new Query($query);
        if (self::ALLOW_SUB_CONDITION === false && $queryObject->haveSubCondition()) {
            throw new SqlParseException('Query have sub condition');
        }
        $specifierCollection = new SpecifierCollection();

        $lexemeParser = new LexemeParser($specifierCollection->getMarkers());
        $lexemes = $lexemeParser->get($queryObject, $arguments);
        $queryObject->checkBlock($lexemes, $this->skip());

        $parameterMapper = new ParameterMapper();

        /** @var Lexeme[] $lexemes */
        foreach ($lexemes as $lexeme) {
            $specifier = $specifierCollection->get($lexeme->specifier);
            $specifier->run($lexeme, $parameterMapper);
        }
        $parameterMapper->prepare($queryObject, $this->mysqli);
        return $queryObject->getSql();
    }

    public function skip()
    {
        return 6;
    }
}
