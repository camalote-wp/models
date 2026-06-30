# CI

GitHub Actions workflow that gates quality before merge and triggers release on push to main. The check job runs tooling commands; the release job triggers semantic-release.

## ADDED Requirements

### Requirement: CI runs quality checks on push and pull request
The project SHALL include a GitHub Actions workflow that runs PHPStan, Pint, and Pest on every push and pull request using PHP 8.2, with all tooling commands executed via inline flags: `--working-dir tooling` for Composer, `--prefix tooling` for npm/npx.

#### Scenario: CI runs on push
- **WHEN** a commit is pushed to any branch
- **THEN** the workflow SHALL run `composer install --working-dir tooling` and `composer check --working-dir tooling`

#### Scenario: CI runs on pull request
- **WHEN** a pull request is opened or updated
- **THEN** the workflow SHALL run `composer install --working-dir tooling` and `composer check --working-dir tooling`

### Requirement: CI release job runs on push to main
The project SHALL include a `release` job that runs only on pushes to `main`, depends on the `check` and `lint-packaging` jobs passing, and executes `npm ci` followed by `npx semantic-release` from the `tooling/` working directory.

#### Scenario: Release job runs on push to main
- **WHEN** a commit is pushed to `main` and the check and lint-packaging jobs pass
- **THEN** the release job SHALL run `npm ci` and `npx semantic-release` from the `tooling/` working directory

#### Scenario: Release job does not run on pull request
- **WHEN** a pull request is opened or updated
- **THEN** the `release` job SHALL NOT execute

### Requirement: CI validates packaging files
The CI pipeline SHALL validate that `.gitattributes` exists and contains `export-ignore` rules before allowing a release.

#### Scenario: CI runs on pull request
- **WHEN** a pull request targets `main`
- **THEN** the CI SHALL verify `.gitattributes` exists and contains at least one `export-ignore` entry

#### Scenario: CI runs on push to main
- **WHEN** code is pushed to `main`
- **THEN** the CI SHALL verify `.gitattributes` exists and contains at least one `export-ignore` entry before the release job runs
