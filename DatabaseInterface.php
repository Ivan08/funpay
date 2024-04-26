<?php

namespace FpDbTest;

interface DatabaseInterface
{
    public function buildQuery(string $query, array $arguments = []): string;

    public function skip();
}
