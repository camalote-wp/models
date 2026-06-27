<?php

namespace CamaloteWP\Models\Core;

class Loader
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $actions = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $filters = [];

    /**
     * @return $this
     */
    public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): self
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);

        return $this;
    }

    /**
     * @return $this
     */
    public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): self
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>>  $hooks
     * @return array<int, array<string, mixed>>
     */
    private function add(array $hooks, string $hook, object $component, string $callback, int $priority, int $accepted_args): array
    {
        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];

        return $hooks;
    }

    public function run(): void
    {
        foreach ($this->filters as $hook) {
            \add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
        }
        foreach ($this->actions as $hook) {
            \add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
        }
    }
}
