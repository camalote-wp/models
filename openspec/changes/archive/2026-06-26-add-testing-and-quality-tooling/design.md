## Context

This is an internal Composer library providing abstract base classes for WordPress content model registration (post types, meta, blocks, REST, admin pages). It has 11 PHP files in `src/`, no tests, no static analysis, no code style tooling, and no documentation. The library is used by extending abstracts and calling `BootstrapRunner::run()`.

Every concrete method in the codebase calls a WordPress global function (`register_post_type`, `register_post_meta`, `add_action`, `add_filter`, `register_block_type`, etc.). There is no injection point for these calls — they are hard dependencies. This means testing requires runtime patching to intercept those function calls.

## Goals / Non-Goals

**Goals:**
- Protect against silent breakage (meta prefix dropped, hooks not registered, components skipped)
- Enforce type safety via static analysis
- Enforce consistent code style
- Document the library so the author can use it after months away
- Automate all checks via CI

**Non-Goals:**
- Integration tests against a real WordPress instance
- 100% code coverage
- Refactor source code to be more testable (the direct WP function calls are acceptable for a WP library)
- Mock every WordPress function — only mock what the tests actually call

## Decisions

### D1: Pest over PHPUnit
**Chosen:** Pest v4
**Rationale:** Nicer syntax, already preferred by the author. Pest runs on PHPUnit under the hood, so no capability is lost. Pest v4 uses PHPUnit 12.

### D2: brain/monkey over wp_mock for WP mocking
**Chosen:** brain/monkey
**Rationale:** wp_mock hard-requires PHPUnit ^9.6, which conflicts with Pest v4 (PHPUnit 12). brain/monkey has no PHPUnit dependency and works with any test framework. Both use Patchwork + Mockery under the hood. brain/monkey v2.7.0 was released Feb 2026 and is actively maintained.

### D3: Test bootstrap with Yoast TestCase
**Chosen:** Use `Yoast\WPTestUtils\BrainMonkey\TestCase` as the base test case
**Rationale:** Provides Brain\Monkey setup/teardown automatically, plus common WP function stubs (translation functions, etc.). Standard practice in the WP testing community.

### D4: Minimal test suite focused on silent-breakage scenarios
**Chosen:** ~7 tests covering: meta prefixing, loader array building, BootstrapRunner component wiring, block path registration, hook registration
**Rationale:** The library is thin wrappers. The real risk is silent breakage where WP functions don't throw, they just don't register. These tests catch exactly that class of bug.

### D5: PHPStan level 6
**Chosen:** Level 6 (reporting any type-related bug)
**Rationale:** Level 6 catches wrong arg types, missing methods, broken contracts. Higher levels (7-9) require more annotation effort for marginal return on a library this size. Can increase later.

### D6: Laravel Pint for code style
**Chosen:** Pint with default preset
**Rationale:** Zero configuration, built on PHP-CS-Fixer, opinionated. Exactly what's needed for an internal tool — consistent style with zero decision-making.

### D7: README as living documentation
**Chosen:** README.md with: (1) one-sentence purpose, (2) 4-step usage contract, (3) complete working example per abstract, (4) per-abstract reference table
**Rationale:** For an internal tool used episodically, the documentation IS the test for "can I remember how to use this?" The 4-step contract (extend, implement, list in get_components, call BootstrapRunner::run) is the mental model.

### D8: GitHub Actions CI
**Chosen:** Single workflow on push/PR running PHPStan, Pint, and Pest
**Rationale:** Prevents forgetting to run checks. Single PHP 8.1+ matrix is sufficient for a library this size.

### D8b: captainhook pre-commit hook
**Chosen:** `sebastianfeldmann/captainhook` with a pre-commit hook running `composer check`
**Rationale:** Catches issues locally before they reach CI. Same three commands (stan, pint, test) run at the same commit, zero drift between local and CI. Pure PHP/Composer — no Node/npm dependency. Hook config is versioned via `captainhook.json` and hooks install on `composer install`. Rejected husky because adding an npm toolchain to a PHP library is overkill.

### D9: Remove static BootstrapRunner::run()
**Chosen:** Remove the static `run(string ...$bootstrap_classes)` method, keep only the instance API: `new BootstrapRunner()->register([...])->run()`
**Rationale:** The static `run()` and instance `run()` share the same name, creating a PHP fatal error (cannot redeclare method). The static was a convenience shortcut for `register()->execute()`, but it hides instantiation, prevents inspection between register and run, and makes testing harder. Removing it eliminates the collision and yields a cleaner API with one unambiguous `run()` method. **BREAKING** for any consumer using `BootstrapRunner::run()`.

### D10: Fully qualify all global WP function calls with leading backslash
**Chosen:** Add leading `\` to all global function calls in namespaced files (e.g., `\register_post_type()` instead of `register_post_type()`)
**Rationale:** When PHP encounters an unqualified `register_post_type()` inside a namespace, it resolves through the namespace hierarchy before falling back to global. This is fragile: it works only because no intermediate namespace defines the same name. Explicit `\register_post_type()` removes ambiguity, aids static analysis, helps IDE resolution, and ensures Patchwork/brain/monkey can reliably intercept the calls. Discovered during test implementation — `Functions\expect()` on `add_action`/`add_filter` failed to intercept calls from `Loader::run()`, likely due to Patchwork's source preprocessing not fully resolving unqualified calls in namespaced files.

### D11: MockeryTestCase + Monkey\tearDown in TestCase::tearDown
**Chosen:** `Tests\TestCase` extends `Mockery\Adapter\Phpunit\MockeryTestCase` and overrides `tearDown()` to call `Monkey\tearDown()` before `parent::tearDown()`. Drop `afterEach` from `Pest.php` entirely. Remove all `$this->addToAssertionCount()` calls from tests.
**Rationale:** Pest's `afterEach` compiles into the test method body, which runs **before** PHPUnit's `assertPostConditions()`. When `Monkey\tearDown()` (which calls `Mockery::close()`) ran in `afterEach`, it destroyed the Mockery container before `MockeryTestCase::mockeryAssertPostConditions()` could count expectations — causing "risky: no assertions" on every test after the first. Moving `Monkey\tearDown()` into `TestCase::tearDown()` places it in PHPUnit's teardown lifecycle, which runs **after** `assertPostConditions()`. The correct order becomes: test body → `assertPostConditions` (counts expectations + closes Mockery) → `tearDown` (Monkey\tearDown resets Container + Patchwork, Mockery::close is harmless second call) → `purgeMockeryContainer`. No hacks, no `failOnRisky`, no manual assertion counting. MockeryTestCase handles everything as designed.

## Risks / Trade-offs

- brain/monkey uses Patchwork for runtime patching → Mitigation: standard tool, widely used, no alternative without refactoring source code
- Tests mock WP functions but can't verify actual WP behavior → Mitigation: acceptable for a registration-gllue library; if real WP behavior verification is ever needed, add pest-wp-plugin integration tests later
- README examples may drift from code → Mitigation: keep examples minimal and focused on the stable 4-step contract
- Static BootstrapRunner::run() removal is breaking → Mitigation: internal library with minimal consumer surface; migration is a one-line change from `BootstrapRunner::run(X::class)` to `(new BootstrapRunner)->register([X::class])->run()`
