## Why

This codebase currently functions as a WordPress plugin but needs to become a pure Composer library. Consumers should define their own model definitions and handle plugin instantiation, while this library provides only the abstract contracts and base implementations for building WordPress content models.

## What Changes

- **BREAKING**: Remove `plugin.php` (WP plugin bootstrap)
- **BREAKING**: Remove `app/Core/Plugin.php` (plugin orchestration)
- **BREAKING**: Remove `app/Core/Activator.php` and `app/Core/Deactivator.php` (empty, plugin lifecycle)
- **BREAKING**: Remove `app/Core/I18n.php` (plugin-specific textdomain)
- **BREAKING**: Inline `AdminPageRegistrar` into `AbstractModelAdminPage` and remove `Core/AdminPageRegistrar.php`
- **BREAKING**: Remove `app/Definitions/` entirely (moved to consumer plugins)
- **BREAKING**: Remove `assets/` folder entirely (moved to consumer plugins)
- **BREAKING**: Remove `webpack.config.js` and `package.json` (build tooling moved to consumer)
- Add `get_block_paths(): array` abstract method to `AbstractBlocks`
- Add `get_meta_prefix(): string` method to `AbstractMeta` (opt-in prefix)
- Create `src/Core/Loader.php` and `src/Core/BootstrapRunner.php` utility helpers
- Update `composer.json` to set type as `library` and rename `app/` to `src/`

## Capabilities

### New Capabilities
- `model-registration`: Contracts and base classes for registering WordPress models (post types, meta, blocks, REST endpoints, admin pages)

### Modified Capabilities
- None

## Impact

- All concrete Definitions will move to consumer plugins
- Consumers must implement their own plugin bootstrap that instantiates Definitions
- Block and asset paths are now consumer responsibilities
- No more shared CSS/assets enqueued by the library
- Library becomes a pure PHP package with no WP plugin overhead