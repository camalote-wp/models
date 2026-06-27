## Why

This library has no testing, static analysis, code style enforcement, or documentation. As an internal boilerplate reducer used across multiple WordPress plugins, it needs a regression safety net to prevent silent breakage (e.g., meta prefixes dropped, hooks not registered) and a README so the author can remember how to use it after months away.

## What Changes

- Add Pest as the test framework with brain/monkey for mocking WordPress functions
- Add ~7 unit tests covering the silent-breakage scenarios (meta prefix, hook registration, component wiring, block registration)
- **BREAKING**: Remove static `BootstrapRunner::run()` — use `$runner->register()->run()` instead (fixes name collision between static and instance `run()`)
- Qualify all global WP function calls with leading backslash (e.g., `\register_post_type()` instead of `register_post_type()`) for explicit resolution and reliable test interception
- Add PHPStan at level 6 for static analysis
- Add Laravel Pint for code style
- Add a README with usage contract and working examples
- Add GitHub Actions CI workflow running Pest, PHPStan, and Pint
- Add captainhook pre-commit hook running the same `composer check` before every commit

## Capabilities

### New Capabilities
- `testing`: Unit test suite using Pest + brain/monkey, covering registration calls, meta prefixing, loader wiring, and block path handling
- `quality-tooling`: PHPStan static analysis, Pint code style, and CI workflow configuration
- `documentation`: README with library purpose, 4-step usage contract, per-abstract reference, and complete working examples

### Modified Capabilities
- `model-registration`: Global WP function calls in abstracts and core classes become fully qualified with leading backslash; BootstrapRunner instance API replaces static `run()`

## Impact

- composer.json gains require-dev dependencies (pest, brain/monkey, phpstan, pint)
- New `tests/` directory with Pest bootstrap and test files
- New `phpstan.neon` configuration
- New `.github/workflows/ci.yml` for CI
- New `README.md` at project root
- `src/Core/BootstrapRunner.php` changes: remove static `run()`, rename instance `execute()` back to `run()`
- All `src/` files: qualify global function calls with leading backslash
