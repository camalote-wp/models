<?php

use CamaloteWP\Models\Core\Loader;

it('builds action arrays correctly', function () {
    $loader = new Loader;
    $component = new class {};

    $loader->add_action('init', $component, 'register', 20, 2);

    $reflection = new ReflectionClass($loader);
    $actions = $reflection->getProperty('actions')->getValue($loader);

    expect($actions)->toHaveCount(1);
    expect($actions[0])->toBe([
        'hook' => 'init',
        'component' => $component,
        'callback' => 'register',
        'priority' => 20,
        'accepted_args' => 2,
    ]);
});

it('builds filter arrays correctly', function () {
    $loader = new Loader;
    $component = new class {};

    $loader->add_filter('the_content', $component, 'filter_content', 5, 3);

    $reflection = new ReflectionClass($loader);
    $filters = $reflection->getProperty('filters')->getValue($loader);

    expect($filters)->toHaveCount(1);
    expect($filters[0])->toBe([
        'hook' => 'the_content',
        'component' => $component,
        'callback' => 'filter_content',
        'priority' => 5,
        'accepted_args' => 3,
    ]);
});

it('defaults priority to 10 and accepted_args to 1', function () {
    $loader = new Loader;
    $component = new class {};

    $loader->add_action('init', $component, 'register');

    $reflection = new ReflectionClass($loader);
    $actions = $reflection->getProperty('actions')->getValue($loader);

    expect($actions[0]['priority'])->toBe(10);
    expect($actions[0]['accepted_args'])->toBe(1);
});
