<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Registerable;

abstract class AbstractPostType implements Registerable {
    protected string $model_name;

    abstract protected function args(): array;

    public function register(): void {
        register_post_type( $this->model_name, $this->args() );
    }
}