<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use Tests\TestCase;

pest()->extend(TestCase::class)->in('Feature', 'Unit');

beforeEach(function () {
    Monkey\setUp();
    Functions\when('__')->returnArg(1);
    Functions\when('_e')->returnArg(1);
    Functions\when('_x')->returnArg(1);
    Functions\when('esc_html')->returnArg(1);
    Functions\when('esc_attr')->returnArg(1);
    Functions\when('esc_url')->returnArg(1);
});
