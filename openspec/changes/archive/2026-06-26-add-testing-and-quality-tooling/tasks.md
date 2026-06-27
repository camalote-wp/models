## 1. Dependencies and Configuration

- [x] 1.1 Add pest/pest, brain/monkey, yoast/phpunit-polyfills to composer.json require-dev
- [x] 1.2 Add phpstan/phpstan and laravel/pint to composer.json require-dev
- [x] 1.3 Add composer scripts: test, stan, pint, check
- [x] 1.4 Create phpstan.neon with level 6 scanning src/
- [x] 1.5 Run composer update and verify all dependencies resolve

## 2. Test Bootstrap

- [x] 2.1 Initialize Pest with vendor/bin/pest --init
- [x] 2.2 Create Pest.php bootstrap with Monkey\setUp/tearDown and translation function stubs
- [x] 2.3 Verify bootstrap works by running a placeholder test

## 3. Source Fixes

- [x] 3.1 Remove static BootstrapRunner::run(), rename instance execute() back to run()
- [x] 3.2 Qualify all global WP function calls in src/ with leading backslash
- [x] 3.3 Verify delta specs align with current codebase after source fixes

## 4. Unit Tests

- [x] 4.1 Write test for AbstractMeta: prefixed keys call register_post_meta with prefix
- [x] 4.2 Write test for AbstractMeta: empty prefix passes keys unchanged
- [x] 4.3 Write test for Loader: add_action and add_filter build correct arrays
- [x] 4.4 Write test for BootstrapRunner: Registerable components wired to init hook
- [x] 4.5 Write test for BootstrapRunner: Hookable components have hooks added
- [x] 4.6 Write test for AbstractBlocks: valid paths register blocks
- [x] 4.7 Write test for AbstractBlocks: invalid paths are skipped

## 5. Test Lifecycle Fix

- [x] 5.1 Change Tests\TestCase to extend MockeryTestCase, remove manual MockeryPHPUnitIntegration trait
- [x] 5.2 Remove all $this->addToAssertionCount() calls from tests
- [x] 5.3 Move Monkey\tearDown() from Pest.php afterEach into TestCase::tearDown(), drop afterEach entirely
- [x] 5.4 Verify all tests pass with no risky warnings

## 6. Quality Tooling

- [x] 6.1 Run phpstan and fix all errors in src/
- [x] 6.2 Run pint and fix any style violations in src/
- [x] 6.3 Verify composer check runs all three tools in sequence
- [x] 6.4 Add prose DocBlocks to interfaces and abstract/overridable methods (Interface contracts only, skip concrete methods where names are self-documenting)
- [x] 6.5 Install captainhook and configure pre-commit hook for composer check
- [x] 6.6 Verify pre-commit hook fires on commit and blocks on failure

## 7. Documentation

- [x] 7.1 Write README.md with library purpose and 4-step usage contract
- [x] 7.2 Add per-abstract reference section (what to extend, what to implement, what you get)
- [x] 7.3 Add complete working example with post type, meta, blocks, and bootstrap

## 8. CI

- [x] 8.1 Create .github/workflows/ci.yml running PHPStan, Pint, and Pest on push and PR with PHP 8.1
- [x] 8.2 Verify CI workflow runs successfully
