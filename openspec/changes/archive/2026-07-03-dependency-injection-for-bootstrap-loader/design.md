## Context

The BootstrapRunner class in `/workspaces/models/src/Core/BootstrapRunner.php` currently creates its own Loader instance in its constructor (line 14: `$this->loader = new Loader;`). This encapsulation prevents external classes from accessing the same Loader instance, which is problematic when plugin initialization needs to register hooks (like localization) that should execute during the same bootstrap run.

Our Plugin class follows a pattern where it exposes its Loader via a `get_loader()` method, but this getter isn't currently used in the Plugin implementation. The BootstrapRunner lacks any mechanism to share its Loader instance.

## Goals / Non-Goals

**Goals:**
- Allow external classes to share the Loader instance used by BootstrapRunner
- Maintain backward compatibility - existing code using `new BootstrapRunner()` must continue to work
- Follow the existing pattern established by the Plugin class
- Enable plugin initialization hooks to register with the same Loader as bootstrap classes

**Non-Goals:**
- Changing the core responsibility of BootstrapRunner (it still orchestrates bootstrap class execution)
- Removing the internal Loader creation capability
- Changing how bootstrap classes are registered or executed
- Introducing a full dependency injection container (overkill for this use case)

## Decisions

### Constructor Injection with Default Parameter
**Decision:** Modify BootstrapRunner constructor to accept an optional Loader parameter: `__construct(?Loader $loader = null)`
- When `$loader` is null, create a new Loader instance (current behavior)
- When `$loader` is provided, use that instance
- **Why:** This maintains backward compatibility while enabling dependency injection
- **Alternative considered:** Requiring Loader as mandatory parameter (breaking change) - rejected due to compatibility concerns
- **Alternative considered:** Adding a setter method - rejected as less clear than constructor injection for required dependencies

### Getter Method for Loader Access
**Decision:** Add a public `get_loader(): Loader` method that returns the Loader instance
- **Why:** Provides consistent API with Plugin class's `get_loader()` method
- **Why:** Enables external registration of initialization-phase hooks
- **Alternative considered:** Exposing Loader via public property - rejected as violates encapsulation
- **Alternative considered:** Only providing hook registration methods on BootstrapRunner - rejected as too restrictive

## Risks / Trade-offs

### Risk: Breaking existing extensions that extend BootstrapReader
[If anyone has extended BootstrapRunner and overridden the constructor, the new optional parameter could cause issues] → Mitigation: The change is backward compatible as the new parameter is optional with a default value

### Risk: Misunderstanding of the Loader's purpose
[Developers might think they can now use the Loader for any hook registration, not just initialization] → Mitigation: Clear documentation specifying this is for initialization-phase hooks

### Risk: Test breakage
[Existing tests instantiate BootstrapRunner without parameters] → Mitigation: Tests will continue to work due to default parameter; we'll update tests to also validate the new constructor signature

## Open Questions

None - the approach is straightforward and follows established patterns in the codebase