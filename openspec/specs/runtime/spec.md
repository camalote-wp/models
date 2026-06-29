## Requirements

### Requirement: Consumer defines model components via AbstractBootstrap
The library SHALL provide `AbstractBootstrap` that consumers extend to define reusable model components.

#### Scenario: Model with multiple components
- **WHEN** a consumer creates a Bootstrap extending `AbstractBootstrap` with `PostType`, `Meta`, and `Blocks` in `get_components()`
- **THEN** the consumer can iterate components for registration

### Requirement: AbstractPostType registers WordPress post type
The library SHALL provide `AbstractPostType` that calls `\register_post_type()` using child-defined arguments.

#### Scenario: Concrete post type registration
- **WHEN** a consumer creates a class extending `AbstractPostType` and defines `args()`
- **THEN** calling `register()` registers the post type with those arguments via the fully qualified global function

### Requirement: AbstractMeta registers post meta with overridable prefix
The library SHALL provide `AbstractMeta` that calls `\register_post_meta()` for each schema entry, with `get_meta_prefix()` returning empty string by default.

#### Scenario: Meta registration with custom prefix
- **WHEN** a consumer extends `AbstractMeta` and optionally overrides `get_meta_prefix()` returning a prefix
- **THEN** all meta keys are prefixed; default behavior returns keys unchanged

### Requirement: AbstractBlocks auto-discovers block.json in consumer-defined paths
The library SHALL provide `AbstractBlocks` that discovers and registers blocks from paths defined by the consumer using `\is_dir()`, `\glob()`, and `\register_block_type()`.

#### Scenario: Block registration from custom path
- **WHEN** a consumer extends `AbstractBlocks` and implements `get_block_paths()` returning their block directories
- **THEN** `register()` auto-discovers all `block.json` files and registers them

### Requirement: AbstractRest provides hooks pattern for REST extension
The library SHALL provide `AbstractRest` that consumers extend to add REST endpoint filters and actions.

#### Scenario: Extending REST response
- **WHEN** a consumer extends `AbstractRest` and implements `get_hooks()` with REST filters
- **THEN** their callbacks are invoked for REST operations

### Requirement: AbstractModelAdminPage provides admin page abstraction
The library SHALL provide `AbstractModelAdminPage` that allows consumers to define admin pages with asset configuration via `get_asset_config()`.

#### Scenario: Admin page with assets
- **WHEN** a consumer extends `AbstractModelAdminPage` and implements `get_asset_config()` returning asset paths and handles
- **THEN** `enqueue_assets()` loads scripts/styles from those paths

### Requirement: Loader provides hook aggregation utility
The library SHALL provide `Loader` that collects actions and filters and applies them via `\add_action()` and `\add_filter()`.

#### Scenario: Hook registration via loader
- **WHEN** a consumer adds hooks via `Loader::add_action()` and `Loader::add_filter()`
- **THEN** calling `run()` registers all hooks with WordPress

### Requirement: BootstrapRunner provides Definition registration helper
The library SHALL provide `BootstrapRunner` that consumers instantiate and call `register()` then `run()` to instantiate Definition components and register them.

#### Scenario: Runner registers model components
- **WHEN** a consumer calls `(new BootstrapRunner)->register([Page\Bootstrap::class, Event\Bootstrap::class])->run()`
- **THEN** the runner instantiates and registers all components implementing `Registerable`
