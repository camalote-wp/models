<?php

use Brain\Monkey\Functions;
use CamaloteWP\Models\Abstracts\AbstractBlocks;

it('registers blocks from valid paths containing block.json', function () {
    Functions\expect('is_dir')
        ->once()
        ->with('/blocks')
        ->andReturn(true);

    Functions\expect('glob')
        ->once()
        ->with('/blocks/*/block.json')
        ->andReturn(['/blocks/hero/block.json', '/blocks/cta/block.json']);

    Functions\expect('register_block_type')
        ->once()
        ->with('/blocks/hero')
        ->andReturn(true);

    Functions\expect('register_block_type')
        ->once()
        ->with('/blocks/cta')
        ->andReturn(true);

    $blocks = new class extends AbstractBlocks
    {
        protected string $model_name = 'page';

        protected function get_block_paths(): array
        {
            return ['/blocks'];
        }
    };

    $blocks->register();
});

it('skips block paths that do not exist on disk', function () {
    Functions\expect('is_dir')
        ->once()
        ->with('/nonexistent')
        ->andReturn(false);

    $blocks = new class extends AbstractBlocks
    {
        protected string $model_name = 'page';

        protected function get_block_paths(): array
        {
            return ['/nonexistent'];
        }
    };

    $blocks->register();
});
