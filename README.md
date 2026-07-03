# CamaloteWP Models

Abstract base classes for registering WordPress content models (post types, meta, blocks, REST hooks, admin pages). Reduces boilerplate when spinning up the same registration logic across multiple plugins.

## Usage

1. Extend the abstracts for your content model
2. Implement the abstract methods
3. List your components in a Bootstrap class
4. Run the BootstrapRunner

```php
use CamaloteWP\Models\Core\BootstrapRunner;

// Standard usage (creates internal Loader)
$runner = new BootstrapRunner;
$runner->register([Page\Bootstrap::class])->run();

// With dependency injection (shares Loader instance)
$loader = new Loader;
$runner = new BootstrapRunner($loader);
$runner->register([Page\Bootstrap::class])->run();

// You can also access the shared loader for additional hook registration
$loader->add_action('plugins_loaded', $this, 'load_textdomain');
```

## Complete Example

```php
// PagePostType.php
namespace MyPlugin\Page;

use CamaloteWP\Models\Abstracts\AbstractPostType;

class PagePostType extends AbstractPostType
{
    protected string $model_name = 'page';

    protected function args(): array
    {
        return [
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'labels' => [
                'name' => 'Pages',
                'singular_name' => 'Page',
            ],
        ];
    }
}
```

```php
// PageMeta.php
namespace MyPlugin\Page;

use CamaloteWP\Models\Abstracts\AbstractMeta;

class PageMeta extends AbstractMeta
{
    protected string $model_name = 'page';

    protected function schema(): array
    {
        return [
            'subtitle' => ['type' => 'string'],
            'color'    => ['type' => 'string'],
        ];
    }

    protected function get_meta_prefix(): string
    {
        return 'page_';
    }
}
```

```php
// PageBlocks.php
namespace MyPlugin\Page;

use CamaloteWP\Models\Abstracts\AbstractBlocks;

class PageBlocks extends AbstractBlocks
{
    protected string $model_name = 'page';

    protected function get_block_paths(): array
    {
        return [get_template_directory() . '/blocks'];
    }
}
```

```php
// Bootstrap.php
namespace MyPlugin\Page;

use CamaloteWP\Models\Abstracts\AbstractBootstrap;

class Bootstrap extends AbstractBootstrap
{
    protected string $model_name = 'page';

    public function get_components(): array
    {
        return [
            PagePostType::class,
            PageMeta::class,
            PageBlocks::class,
        ];
    }
}
```

```php
// In your plugin's main file or service provider
use CamaloteWP\Models\Core\BootstrapRunner;
use MyPlugin\Page\Bootstrap;

$runner = new BootstrapRunner;
$runner->register([Bootstrap::class])->run();
```

## Reference

### AbstractPostType

| What to extend | `AbstractPostType` |
|---|---|
| **Implements** | `Registerable` |
| **Must implement** | `args(): array` |
| **Can override** | (none) |
| **You get** | `register()` — calls `\register_post_type()` with `$model_name` and `args()` |

### AbstractMeta

| What to extend | `AbstractMeta` |
|---|---|
| **Implements** | `Registerable` |
| **Must implement** | `schema(): array` |
| **Can override** | `get_meta_prefix(): string` (default: empty string) |
| **You get** | `register()` — loops schema and calls `\register_post_meta()` per entry, prefixing keys with `get_meta_prefix()` |

### AbstractBlocks

| What to extend | `AbstractBlocks` |
|---|---|
| **Implements** | `Registerable`, `Hookable` |
| **Must implement** | `get_block_paths(): array` |
| **Can override** | `get_hooks(): array` (default: empty) |
| **You get** | `register()` — scans each path for `block.json` files and calls `\register_block_type()` |

### AbstractRest

| What to extend | `AbstractRest` |
|---|---|
| **Implements** | `Hookable` |
| **Must implement** | (none) |
| **Can override** | `get_hooks(): array` (default: empty) |
| **You get** | A Hookable base for adding REST filters/actions via `get_hooks()` |

### AbstractModelAdminPage

| What to extend | `AbstractModelAdminPage` |
|---|---|
| **Implements** | `Hookable`, `AdminPage` |
| **Must implement** | `get_page_config(): array`, `get_asset_config(): array`, `render_page(): void` |
| **Can override** | `get_hooks(): array` (default: empty) |
| **You get** | `register_submenu_page()`, `register_menu_page()`, `enqueue_assets()` |

### AbstractBootstrap

| What to extend | `AbstractBootstrap` |
|---|---|
| **Must implement** | `get_components(): array` |
| **Can override** | `register()` (default: empty) |
| **You get** | `get_model_name()` |

### BootstrapRunner

Orchestrates registration. Instantiate, pass Bootstrap class names to `register()`, then call `run()`. Accepts an optional `Loader` instance for dependency injection.

```php
// Default constructor (backward compatible)
$runner = new BootstrapRunner;
$runner->register([Bootstrap::class])->run();

// With dependency injection
$loader = new Loader;
$runner = new BootstrapRunner($loader);
$runner->register([Bootstrap::class])->run();

// Access shared loader (optional)
$loader = $runner->get_loader();
$loader->add_action('init', $this, 'custom_init');
```

## Installation

```bash
composer require camalote-wp/models
```

## Development

```bash
composer test          # Run tests
composer stan          # Run static analysis
composer pint          # Check code style
composer check         # Run all three
```
