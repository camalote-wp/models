<?php

namespace CamaloteWP\Models\Interfaces;

interface Hookable
{
    /**
     * Return the WordPress hooks this component subscribes to.
     *
     * Each entry is an array with:
     *   - type:          string 'action' or 'filter'
     *   - hook:          string The WordPress hook name
     *   - callback:      string Method name on this component
     *   - priority:      int    Hook priority (default 10)
     *   - accepted_args: int    Number of args the callback accepts (default 1)
     *
     * @return array<int, array{type: string, hook: string, callback: string, priority: int, accepted_args: int}>
     */
    public function get_hooks(): array;
}
