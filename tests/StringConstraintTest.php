<?php

namespace korchasa\matched\Tests;

use korchasa\matched\JsonConstraint;
use korchasa\matched\StringConstraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class StringConstraintTest extends TestCase
{
    public function testError()
    {
        $constraint = new StringConstraint('some***short***string');

        try {
            $constraint->evaluate('sOme short string');
            $this->fail('Test must fail on second symbol');
        } catch (ExpectationFailedException $e) {
            $this->assertNotFalse(
                strpos($e->getMessage(), "-some***short***string\n+sOme short string")
            );
        }
    }
}
