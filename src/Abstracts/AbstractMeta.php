<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Registerable;

abstract class AbstractMeta implements Registerable
{
    protected string $model_name;

    /**
     * Define the meta schema as key-value pairs.
     *
     * Each key is the meta key name, each value is the array of arguments
     * passed to register_post_meta() (type, description, single, etc.).
     *
     * @return array<string, array<string, mixed>>
     */
    abstract protected function schema(): array;

    /**
     * Override to prefix meta keys. Returns empty string by default.
     */
    protected function get_meta_prefix(): string
    {
        return '';
    }

    public function register(): void
    {
        foreach ($this->schema() as $key => $args) {
            $prefixed_key = $this->get_meta_prefix().$key;
            \register_post_meta($this->model_name, $prefixed_key, $args);
        }
    }
}
