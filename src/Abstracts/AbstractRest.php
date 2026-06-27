<?php

namespace CamaloteWP\Models\Abstracts;

use CamaloteWP\Models\Interfaces\Hookable;

abstract class AbstractRest implements Hookable
{
    protected string $model_name;

    public function get_hooks(): array
    {
        return [];
    }
}
