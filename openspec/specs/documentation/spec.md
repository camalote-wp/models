# Documentation

README and inline documentation for the library.

## ADDED Requirements

### Requirement: README with library purpose and usage contract
The project SHALL include a README.md that explains the library's purpose as a boilerplate reducer for WordPress content model registration and states the 4-step usage contract: (1) extend the abstracts, (2) implement the abstract methods, (3) list components in get_components(), (4) call BootstrapRunner::run().

#### Scenario: New user understands the contract
- **WHEN** a developer reads the README
- **THEN** they SHALL see the 4-step usage contract clearly stated

### Requirement: Per-abstract reference in README
The README SHALL include a reference section for each abstract class listing: what to extend, what abstract methods to implement, and what you get for free.

#### Scenario: Developer looks up AbstractMeta usage
- **WHEN** a developer reads the per-abstract reference for AbstractMeta
- **THEN** they SHALL see that they must implement `schema()` and may override `get_meta_prefix()`, and that `register()` is provided

### Requirement: Complete working example in README
The README SHALL include a complete, copy-pasteable example showing a concrete Definition for a post type with meta, blocks, and a bootstrap class, wired together with BootstrapRunner.

#### Scenario: Developer copies the example
- **WHEN** a developer copies the working example from the README
- **THEN** the code SHALL be a valid, runnable use of the library that registers a post type, meta, and blocks
