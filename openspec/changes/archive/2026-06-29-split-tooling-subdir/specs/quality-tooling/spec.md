# Quality Tooling

Static analysis, code style enforcement, CI workflow, and pre-commit hooks. All tooling lives in the `tooling/` subdir with its own dependency graph, isolated from the consumer-facing root `composer.json`. Daily development uses proxy scripts in root that delegate via `--working-dir tooling`.

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
The project SHALL include a phpstan.neon configuration file with rule level 6, scanning the `src/` directory, and reporting any type-related errors. The config SHALL live at `tooling/phpstan.neon` with paths relative to the tooling subdir (`../src`, `../tests`).

#### Scenario: Static analysis runs without errors
- **WHEN** `composer stan` is executed from repo root
- **THEN** it SHALL proxy to `composer stan --working-dir tooling` and report zero errors against `../src/` at level 6

### Requirement: Laravel Pint code style enforcement
The project SHALL include Pint as a dev dependency with default configuration.

#### Scenario: Code style is consistent
- **WHEN** `composer pint` is executed from repo root
- **THEN** it SHALL proxy to `composer pint --working-dir tooling` and report zero violations

### Requirement: Composer scripts for quality checks
`tooling/composer.json` SHALL define Composer scripts for `test`, `stan`, `pint`, and `check` (all three combined). Root `composer.json` SHALL define proxy scripts that delegate via `--working-dir tooling`.

#### Scenario: Single command runs all checks
- **WHEN** `composer check` is executed from repo root
- **THEN** it SHALL proxy to `composer check --working-dir tooling` and run PHPStan, Pint, and Pest in sequence

### Requirement: GitHub Actions CI workflow
The project SHALL include a GitHub Actions workflow that runs PHPStan, Pint, and Pest on every push and pull request using PHP 8.2, with all tooling commands executed via inline flags: `--working-dir tooling` for Composer, `--prefix tooling` for npm/npx.

#### Scenario: CI runs on push
- **WHEN** a commit is pushed to any branch
- **THEN** the workflow SHALL run `composer install --working-dir tooling` and `composer check --working-dir tooling`

#### Scenario: CI runs on pull request
- **WHEN** a pull request is opened or updated
- **THEN** the workflow SHALL run `composer install --working-dir tooling` and `composer check --working-dir tooling`

#### Scenario: Release job runs on push to main
- **WHEN** a commit is pushed to `main`
- **THEN** the release job SHALL run after check, and SHALL execute `npm ci --prefix tooling` and `npx semantic-release --prefix tooling`

### Requirement: CI release job uses semantic-release
The GitHub Actions workflow SHALL include a `release` job that:
- Runs only on pushes to `main`
- Depends on the `check` job passing
- Uses `--prefix tooling` flag so `npm ci` and `npx semantic-release` operate in tooling/

#### Scenario: Release succeeds on feat commit
- **WHEN** a `feat:` commit is pushed to `main` and the check job passes
- **THEN** the release job SHALL create a new tag and GitHub release

#### Scenario: Release skips on non-conventional commits
- **WHEN** a commit without conventional prefix is pushed to `main`
- **THEN** the release job SHALL complete without creating a release

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
