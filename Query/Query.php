<?php

namespace FpDbTest\Query;

final class Query
{
    /**
     * @param string $sql
     */
    public function __construct(private string $sql)
    {
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return bool
     */
    public function haveSubCondition(): bool
    {
        $c = 0;
        for ($i = 0; $i < strlen($this->sql); $i++) {
            match ($this->sql[$i]) {
                '{' => $c++,
                '}' => $c--,
                default => null
            };
            if ($c == 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $lexemes
     * @param mixed $skip
     * @return void
     */
    public function checkBlock(array $lexemes, mixed $skip): void
    {
        preg_match_all('/\{.*?\}/', $this->sql, $matches);
        if (isset($matches[0])) {
            foreach ($matches[0] as $match) {
                preg_match_all(sprintf('/(%s)/', implode('|', array_keys($lexemes))), $match, $mods1);
                foreach ($mods1[0] as $item) {
                    if ($lexemes[$item]->contains($skip)) {
                        $this->replace($match, '');
                        break;
                    }
                }
            }
            $this->removeBrackets();
        }
    }

    /**
     * @param string $search
     * @param string $replace
     * @return void
     */
    public function replace(string $search, string $replace): void
    {
        $this->sql = str_replace($search, $replace, $this->sql);
    }

    /**
     * @return void
     */
    private function removeBrackets(): void
    {
        $this->sql = str_replace(['{', '}'], '', $this->sql);
    }

    /**
     * @param string $search
     * @param string $replace
     * @return void
     */
    public function replaceFirst(string $search, string $replace): void
    {
        $search = '/' . preg_quote($search, '/') . '/';
        $this->sql = preg_replace($search, $replace, $this->sql, 1);
    }
}
