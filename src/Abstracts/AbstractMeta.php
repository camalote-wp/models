<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Registerable;

abstract class AbstractMeta implements Registerable {
    protected string $model_name;

    abstract protected function schema(): array;

    protected function get_meta_prefix(): string {
        return '';
    }

    public function register(): void {
        foreach ( $this->schema() as $key => $args ) {
            $prefixed_key = $this->get_meta_prefix() . $key;
            register_post_meta( $this->model_name, $prefixed_key, $args );
        }
    }
}