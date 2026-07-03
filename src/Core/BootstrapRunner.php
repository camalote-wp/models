<?php

namespace CamaloteWP\Models\Core;

use CamaloteWP\Models\Interfaces\Hookable;
use CamaloteWP\Models\Interfaces\Registerable;

class BootstrapRunner
{
    protected Loader $loader;

    public function __construct(?Loader $loader = null)
    {
        $this->loader = $loader ?? new Loader;
    }

    /**
     * Get the loader instance used for hook registration.
     *
     * @return Loader The loader instance
     */
    public function get_loader(): Loader
    {
        return $this->loader;
    }

    /**
     * @param  array<int, string>  $bootstrap_classes
     */
    public function register(array $bootstrap_classes): self
    {
        foreach ($bootstrap_classes as $bootstrap_class) {
            $bootstrap = $this->instantiate($bootstrap_class);

            foreach ($bootstrap->get_components() as $component_class) {
                $instance = new $component_class;

                if ($instance instanceof Registerable) {
                    $this->loader->add_action('init', $instance, 'register');
                }

                if ($instance instanceof Hookable) {
                    foreach ($instance->get_hooks() as $hook) {
                        $method = ($hook['type'] === 'filter') ? 'add_filter' : 'add_action';
                        $this->loader->$method(
                            $hook['hook'],
                            $instance,
                            $hook['callback'],
                            $hook['priority'],
                            $hook['accepted_args']
                        );
                    }
                }
            }
        }

        return $this;
    }

    protected function instantiate(string $bootstrap_class): object
    {
        return new $bootstrap_class;
    }

    public function run(): void
    {
        $this->loader->run();
    }
}
