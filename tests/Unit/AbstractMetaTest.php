<?php

use Brain\Monkey\Functions;
use CamaloteWP\Models\Abstracts\AbstractMeta;

it('registers meta with prefixed keys', function () {
    Functions\expect('register_post_meta')
        ->once()
        ->with('team', 'team_color', ['type' => 'string']);

    $meta = new class extends AbstractMeta
    {
        protected string $model_name = 'team';

        protected function schema(): array
        {
            return ['color' => ['type' => 'string']];
        }

        protected function get_meta_prefix(): string
        {
            return 'team_';
        }
    };

    $meta->register();
});

it('registers meta with bare keys when prefix is empty', function () {
    Functions\expect('register_post_meta')
        ->once()
        ->with('team', 'color', ['type' => 'string']);

    $meta = new class extends AbstractMeta
    {
        protected string $model_name = 'team';

        protected function schema(): array
        {
            return ['color' => ['type' => 'string']];
        }
    };

    $meta->register();
});
