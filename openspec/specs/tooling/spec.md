# Tooling

Static analysis, code style enforcement, pre-commit hooks, and the tooling subdir structure. All tooling lives in the `tooling/` subdir with its own dependency graph, isolated from the consumer-facing root `composer.json`.

## ADDED Requirements

### Requirement: Tooling subdir contains all dev dependencies
All development tooling dependencies SHALL live in `tooling/composer.json` and `tooling/package.json`. The root `composer.json` SHALL contain only the library's runtime dependency (`php: ^8.2`) and SHALL NOT have a `require-dev` section.

#### Scenario: Root composer.json has no dev deps
- **WHEN** a consumer reads the root `composer.json`
- **THEN** the file SHALL contain only `"require": { "php": "^8.2" }` and no `require-dev` key

#### Scenario: Tooling composer.json has all dev deps
- **WHEN** a developer reads `tooling/composer.json`
- **THEN** it SHALL contain pest, phpstan, pint, brain/monkey, yoast/phpunit-polyfills, szepeviktor/phpstan-wordpress, and captainhook-phar

### Requirement: Tooling config files live in subdir
The following files SHALL be located in `tooling/`, not at repo root: `phpstan.neon`, `phpunit.xml`, `patchwork.json`, `captainhook.json`, `package.json`, `.releaserc.json`, `package-lock.json`.

#### Scenario: Root contains no tooling config
- **WHEN** a developer lists files at repo root
- **THEN** none of the files listed above SHALL be present

#### Scenario: Tooling subdir contains all config
- **WHEN** a developer lists files in `tooling/`
- **THEN** all files listed above SHALL be present

### Requirement: Tooling vendor and lock files isolated
`tooling/vendor/` and `tooling/composer.lock` SHALL NOT be committed to git.

#### Scenario: Tooling vendor is gitignored
- **WHEN** a developer runs `git status` after `composer install --working-dir tooling`
- **THEN** `tooling/vendor/` SHALL NOT appear in the output

### Requirement: PHPStan static analysis at level 6
The project SHALL include a `phpstan.neon` configuration file at `tooling/phpstan.neon` with rule level 6, scanning the `src/` directory, and reporting any type-related errors. Paths in the config SHALL be relative to the tooling subdir (`../src`, `../tests`).

#### Scenario: Static analysis runs without errors
- **WHEN** `composer stan` is executed from repo root
- **THEN** it SHALL proxy to `composer stan --working-dir tooling` and report zero errors against `../src/` at level 6

### Requirement: Laravel Pint code style enforcement
The project SHALL include Pint as a dev dependency in `tooling/` with default configuration.

#### Scenario: Code style is consistent
- **WHEN** `composer pint` is executed from repo root
- **THEN** it SHALL proxy to `composer pint --working-dir tooling` and report zero violations

### Requirement: Composer scripts for quality checks
`tooling/composer.json` SHALL define Composer scripts for `test`, `stan`, `pint`, and `check` (all three combined). Root `composer.json` SHALL define proxy scripts that delegate via `--working-dir tooling`.

#### Scenario: Single command runs all checks
- **WHEN** `composer check` is executed from repo root
- **THEN** it SHALL proxy to `composer check --working-dir tooling` and run PHPStan, Pint, and Pest in sequence

### Requirement: Pre-commit hook runs quality checks
The project SHALL include a captainhook pre-commit hook that runs `composer check` before every commit, blocking the commit on failure. The `captainhook.json` config SHALL live in `tooling/`. The hook SHALL be installed automatically when running `composer install --working-dir tooling`.

#### Scenario: Pre-commit hook blocks failing commit
- **WHEN** a developer commits code that fails PHPStan, Pint, or Pest
- **THEN** the hook SHALL reject the commit with the failing output

#### Scenario: Pre-commit hook allows passing commit
- **WHEN** a developer commits code that passes all quality checks
- **THEN** the hook SHALL allow the commit to proceed

#### Scenario: Hook config is versioned
- **WHEN** a new developer clones the repo and runs `composer install --working-dir tooling`
- **THEN** captainhook SHALL install the pre-commit hook automatically (hooks installed to root `.git/hooks/`)

### Requirement: semantic-release configuration
The project SHALL include a `.releaserc.json` file at `tooling/.releaserc.json` that configures semantic-release with the conventionalcommits preset. The config SHALL use exactly these plugins: `@semantic-release/commit-analyzer`, `@semantic-release/release-notes-generator`, and `@semantic-release/github`. The `package.json` containing semantic-release as a dev dependency SHALL live at `tooling/package.json`.

#### Scenario: Config is valid
- **WHEN** `npx semantic-release --dry-run --prefix tooling` is executed
- **THEN** it SHALL parse the config without error
