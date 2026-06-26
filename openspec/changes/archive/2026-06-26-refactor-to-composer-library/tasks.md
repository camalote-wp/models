## 1. Remove Plugin-Specific Code

- [x] 1.1 Delete `plugin.php` bootstrap file
- [x] 1.2 Delete `app/Core/Plugin.php` (plugin orchestration)
- [x] 1.3 Delete `app/Core/Activator.php` and `app/Core/Deactivator.php` (empty, plugin lifecycle)
- [x] 1.4 Delete `app/Core/I18n.php` (plugin-specific textdomain)
- [x] 1.5 Inline `AdminPageRegistrar` logic into `AbstractModelAdminPage` and delete `AdminPageRegistrar.php`
- [x] 1.6 Delete `app/Definitions/` directory
- [x] 1.7 Delete `assets/` directory
- [x] 1.8 Delete `webpack.config.js`
- [x] 1.9 Remove JS/WP build dependencies from `package.json` (keep openspec for dev)

## 2. Refactor Abstract Classes

- [x] 2.1 Rename `app/` directory to `src/`
- [x] 2.2 Modify `AbstractMeta` to add `get_meta_prefix(): string` method
- [x] 2.3 Modify `AbstractBlocks` to add `get_block_paths(): array` abstract method and update `register()`
- [x] 2.4 Inline AdminPageRegistrar methods into `AbstractModelAdminPage`
- [x] 2.5 Simplify AbstractBootstrap (remove plugin_name/version)

## 3. Update Composer Configuration

- [x] 3.1 Update `composer.json` to set type as `library`
- [x] 3.2 Update autoload paths to use `src/` directory
- [x] 3.3 Add `require` for `php: ^8.1` minimum
- [x] 3.4 Remove `scripts` section (no build needed)

## 4. Add Core Utility Helpers

- [x] 4.1 Create `src/Core/Loader.php` (generic hook aggregator)
- [x] 4.2 Create `src/Core/BootstrapRunner.php` (Definition registration helper)
- [x] 4.3 Create `src/Core/index.php` (silence is golden)