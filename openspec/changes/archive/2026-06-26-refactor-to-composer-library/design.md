## Context

The codebase currently functions as a WordPress plugin with:
- Plugin bootstrap (`plugin.php`) defining `CAMALOTE_WP_MODELS_*` constants
- Concrete model definitions in `app/Definitions/`
- Webpack-based JS asset compilation in `assets/`
- Abstract base classes tightly coupled to plugin-specific paths

The goal is to extract only the reusable abstractions into a Composer library, allowing consumers to define and instantiate their own model components.

## Goals / Non-Goals

**Goals:**
- Library provides `src/Interfaces/` contracts
- Library provides `src/Abstracts/` with WP function implementations
- Library provides `src/Core/Loader.php` and `src/Core/BootstrapRunner.php` utility helpers
- Consumers define their own Definitions, handling instantiation
- Block paths are injectable via abstract method in AbstractBlocks
- No shared CSS/assets or default model implementations

**Non-Goals:**
- Provide default model implementations (move to consumer)
- Bundle any JavaScript/CSS assets (move to consumer)
- Handle plugin activation/deactivation (consumer responsibility)
- Provide shared styles or scripts (consumer responsibility)

## Decisions

### D1: Inline AdminPageRegistrar into AbstractModelAdminPage
**Chosen:** Move admin page registration logic directly into `AbstractModelAdminPage`
**Rationale:** `AdminPageRegistrar` is only used by one class. Inlining simplifies the codebase.

### D2: Abstract block paths injection
**Chosen:** Add `get_block_paths(): array` abstract method to `AbstractBlocks`
**Rationale:** Consumers provide their own block paths via abstract method, enabling multi-block support per model.

### D3: Meta prefix is opt-in
**Chosen:** Add `get_meta_prefix(): string` method returning empty string by default
**Rationale:** Consumers define their own prefix strategy or use full meta keys directly.

### D4: Provide Core/ utility helpers
**Chosen:** Add `Loader.php` and `BootstrapRunner.php` in `src/Core/`
**Rationale:** Reduces boilerplate for consumers while staying optional.

### D5: Keep `AbstractBootstrap` minimal
**Chosen:** Remove plugin_name/version from constructor
**Rationale:** Consumers instantiate Definitions directly; no plugin bootstrap dependencies.

### D6: Rename `app/` to `src/`
**Chosen:** Align with Composer library conventions
**Rationale:** PSR-4 autoloading typically uses `src/` as the root directory.

## Risks / Trade-offs

- Consumers must implement their own plugin bootstrap → Mitigation: Documentation and examples in README
- Breaking change for any existing consumers → Mitigation: Clear migration path documented in archive