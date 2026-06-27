# Quality Tooling

Static analysis, code style enforcement, CI workflow, and pre-commit hooks.

## ADDED Requirements

### Requirement: PHPStan static analysis at level 6
The project SHALL include a phpstan.neon configuration file with rule level 6, scanning the `src/` directory, and reporting any type-related errors.

#### Scenario: Static analysis runs without errors
- **WHEN** `vendor/bin/phpstan analyse` is executed
- **THEN** it SHALL report zero errors against the `src/` directory at level 6

### Requirement: Laravel Pint code style enforcement
The project SHALL include Pint as a dev dependency with default configuration.

#### Scenario: Code style is consistent
- **WHEN** `vendor/bin/pint --test` is executed
- **THEN** it SHALL report zero violations

### Requirement: Composer scripts for quality checks
The project SHALL define Composer scripts that run all quality tools: `test`, `stan`, `pint`, and `check` (all three combined).

#### Scenario: Single command runs all checks
- **WHEN** `composer check` is executed
- **THEN** it SHALL run PHPStan, Pint, and Pest in sequence

### Requirement: GitHub Actions CI workflow
The project SHALL include a GitHub Actions workflow that runs PHPStan, Pint, and Pest on every push and pull request using PHP 8.2.

#### Scenario: CI runs on push
- **WHEN** a commit is pushed to any branch
- **THEN** the workflow SHALL run PHPStan, Pint, and Pest

#### Scenario: CI runs on pull request
- **WHEN** a pull request is opened or updated
- **THEN** the workflow SHALL run PHPStan, Pint, and Pest

### Requirement: Pre-commit hook runs quality checks
The project SHALL include a captainhook pre-commit hook that runs `composer check` before every commit, blocking the commit on failure.

#### Scenario: Pre-commit hook blocks failing commit
- **WHEN** a developer commits code that fails PHPStan, Pint, or Pest
- **THEN** the hook SHALL reject the commit with the failing output

#### Scenario: Pre-commit hook allows passing commit
- **WHEN** a developer commits code that passes all quality checks
- **THEN** the hook SHALL allow the commit to proceed

#### Scenario: Hook config is versioned
- **WHEN** a new developer clones the repo and runs `composer install`
- **THEN** captainhook SHALL install the pre-commit hook automatically
