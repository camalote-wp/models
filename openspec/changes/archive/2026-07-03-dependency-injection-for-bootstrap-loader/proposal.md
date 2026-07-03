## Why

The BootstrapRunner class currently creates its own Loader instance internally, preventing external components (like the main Plugin class) from sharing the same Loader instance. This creates a situation where hooks registered with different Loader instances won't execute together when the runner's run() method is called. Specifically, plugin initialization hooks (like localization setup) need to share the same Loader as bootstrap classes to ensure all initialization hooks execute during the same run cycle.

## What Changes

- Modify BootstrapRunner constructor to accept an optional Loader dependency
- When Loader is not provided, BootstrapRunner will create its own (maintaining backward compatibility)
- Add getter method for the Loader instance to maintain consistency with Plugin class pattern
- Update BootstrapRunnerTest to test both constructor scenarios

## Capabilities

### Modified Capabilities
- `runtime`: Modifies the BootstrapRunner requirement to allow Loader dependency injection while maintaining backward compatibility

## Impact

- BootstrapRunner constructor signature changes (adds optional parameter)
- Existing code using `new BootstrapRunner()` continues to work unchanged
- New code can share Loader instances: `new BootstrapRunner(new Loader())`
- Plugin classes can now access the same Loader instance used by BootstrapRunner
- Test files will need to be updated to accommodate the new constructor signature
- The BootstrapRunner usage scenario in the runtime spec is extended to show optional Loader injection