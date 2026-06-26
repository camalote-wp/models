<?php

namespace CamaloteWP\Models\Abstracts;

abstract class AbstractBootstrap {
    protected string $model_name;

    abstract public function get_components(): array;

    final public function get_model_name(): string {
        return $this->model_name;
    }

    public function register() {}
}