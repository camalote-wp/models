<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Registerable;

abstract class AbstractPostType implements Registerable
{
    protected string $model_name;

    /**
     * Define the arguments passed to register_post_type().
     *
     * @return array<string, mixed> WordPress post type registration arguments
     */
    abstract protected function args(): array;

    public function register(): void
    {
        \register_post_type($this->model_name, $this->args());
    }
}
