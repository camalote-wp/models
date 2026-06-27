<?php

namespace Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class TestCase extends MockeryTestCase
{
    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
