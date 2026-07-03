## MODIFIED Requirements

### Requirement: BootstrapRunner provides Definition registration helper
The library SHALL provide `BootstrapRunner` that consumers instantiate and call `register()` then `run()` to instantiate Definition components and register them. The BootstrapRunner MAY accept an optional Loader instance via its constructor; if none is provided, it will create its own Loader internally.

#### Scenario: Runner registers model components (default constructor)
- **WHEN** a consumer calls `(new BootstrapRunner)->register([Page\Bootstrap::class, Event\Bootstrap::class])->run()`
- **THEN** the runner instantiates and registers all components implementing `Registerable`

#### Scenario: Runner registers model components with injected Loader
- **WHEN** a consumer creates a Loader instance, passes it to BootstrapRunner's constructor, then calls `register()` and `run()`
- **THEN** the runner uses the provided Loader instance for all operations