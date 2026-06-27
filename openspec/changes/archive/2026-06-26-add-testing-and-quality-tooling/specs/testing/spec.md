## ADDED Requirements

### Requirement: Pest test suite with brain/monkey mocking
The project SHALL include a Pest test suite that uses brain/monkey to mock WordPress functions, enabling unit tests without a WordPress installation.

#### Scenario: Meta key prefixing is verified
- **WHEN** a concrete AbstractMeta provides a non-empty `get_meta_prefix()` return value
- **THEN** the test SHALL assert that `register_post_meta` is called with the prefixed key

#### Scenario: Empty meta prefix passes keys unchanged
- **WHEN** a concrete AbstractMeta uses the default empty `get_meta_prefix()`
- **THEN** the test SHALL assert that `register_post_meta` is called with the bare key

#### Scenario: Loader builds action arrays correctly
- **WHEN** `Loader::add_action()` is called with hook, component, callback, priority, and accepted_args
- **THEN** the internal actions array SHALL contain an entry with all those values

#### Scenario: Loader builds filter arrays correctly
- **WHEN** `Loader::add_filter()` is called with hook, component, callback, priority, and accepted_args
- **THEN** the internal filters array SHALL contain an entry with all those values

#### Scenario: Registerable components are wired to init hook
- **WHEN** a component implementing Registerable is processed by BootstrapRunner
- **THEN** the test SHALL assert that `add_action` is called with the 'init' hook and the component's 'register' method

#### Scenario: Hookable components have their hooks added
- **WHEN** a component implementing Hookable is processed by BootstrapRunner
- **THEN** the test SHALL assert that each hook from `get_hooks()` is added via `add_action` or `add_filter` with correct priority and accepted_args

#### Scenario: Blocks are registered from valid paths
- **WHEN** a concrete AbstractBlocks provides a path containing `block.json` files
- **THEN** the test SHALL assert that `register_block_type` is called for each block

#### Scenario: Invalid block paths are skipped
- **WHEN** a concrete AbstractBlocks provides a path that does not exist on disk
- **THEN** no `register_block_type` call SHALL occur for that path

### Requirement: Test bootstrap with Brain Monkey setup
The test suite SHALL have a `Tests\TestCase` extending `MockeryTestCase` with `Monkey\setUp()` in Pest.php `beforeEach` and `Monkey\tearDown()` in `TestCase::tearDown()`. WordPress translation functions SHALL be stubbed in `beforeEach`.

#### Scenario: Brain Monkey lifecycle is managed per test
- **WHEN** Pest runs a test
- **THEN** `Monkey\setUp()` SHALL be called before each test and `Monkey\tearDown()` SHALL be called after each test via `TestCase::tearDown()`

#### Scenario: WordPress translation functions are stubbed
- **WHEN** test code calls `__()`, `_e()`, or `_x()`
- **THEN** the functions SHALL return their first argument unchanged
