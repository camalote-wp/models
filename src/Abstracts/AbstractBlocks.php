<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Hookable;
use CamaloteWP\Models\Interfaces\Registerable;

abstract class AbstractBlocks implements Hookable, Registerable
{
    protected string $model_name;

    /**
     * Return directories to scan for block.json files.
     *
     * Each path is scanned for subdirectories containing a block.json file.
     *
     * @return array<int, string>
     */
    abstract protected function get_block_paths(): array;

    public function register(): void
    {
        foreach ($this->get_block_paths() as $path) {
            if (\is_dir($path)) {
                foreach (\glob($path.'/*/block.json') as $file) {
                    \register_block_type(\dirname($file));
                }
            }
        }
    }

    public function get_hooks(): array
    {
        return [];
    }
}
