<?php

use Brain\Monkey\Functions;
use CamaloteWP\Models\Abstracts\AbstractBootstrap;
use CamaloteWP\Models\Core\BootstrapRunner;
use CamaloteWP\Models\Interfaces\Hookable;
use CamaloteWP\Models\Interfaces\Registerable;

class StubRegisterable implements Registerable
{
    public function register(): void {}
}

class StubHookable implements Hookable
{
    public function get_hooks(): array
    {
        return [
            ['type' => 'action', 'hook' => 'save_post', 'callback' => 'on_save', 'priority' => 20, 'accepted_args' => 2],
            ['type' => 'filter', 'hook' => 'the_content', 'callback' => 'filter_content', 'priority' => 5, 'accepted_args' => 1],
        ];
    }
}

class StubBootstrapWithRegisterable extends AbstractBootstrap
{
    public function get_components(): array
    {
        return [StubRegisterable::class];
    }
}

class StubBootstrapWithHookable extends AbstractBootstrap
{
    public function get_components(): array
    {
        return [StubHookable::class];
    }
}

it('wires Registerable components to the init hook', function () {
    Functions\expect('add_action')
        ->once()
        ->with('init', Mockery::type('array'), 10, 1);

    Functions\expect('add_filter')
        ->zeroOrMoreTimes();

    $runner = new BootstrapRunner;
    $runner->register([StubBootstrapWithRegisterable::class]);
    $runner->run();
});

it('adds Hookable component hooks via loader', function () {
    Functions\expect('add_filter')
        ->once()
        ->with('the_content', Mockery::type('array'), 5, 1);

    Functions\expect('add_action')
        ->once()
        ->with('save_post', Mockery::type('array'), 20, 2);

    $runner = new BootstrapRunner;
    $runner->register([StubBootstrapWithHookable::class]);
    $runner->run();
});
