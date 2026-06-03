<?php

namespace CamaloteWP\Models\Interfaces;

interface Hookable {
    public function get_hooks(): array;
}