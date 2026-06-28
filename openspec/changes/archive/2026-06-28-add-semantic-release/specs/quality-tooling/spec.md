## MODIFIED Requirements

### Requirement: GitHub Actions CI workflow
The project SHALL include a GitHub Actions workflow that runs PHPStan, Pint, and Pest on every push and pull request using PHP 8.2.

#### Scenario: CI runs on push
- **WHEN** a commit is pushed to any branch
- **THEN** the workflow SHALL run PHPStan, Pint, and Pest

#### Scenario: CI runs on pull request
- **WHEN** a pull request is opened or updated
- **THEN** the workflow SHALL run PHPStan, Pint, and Pest

#### Scenario: Release job runs on push to main
- **WHEN** a commit is pushed to `main`
- **THEN** the release job SHALL run after check, and SHALL execute `npx semantic-release`

## ADDED Requirements

### Requirement: CI release job uses semantic-release
The GitHub Actions workflow SHALL include a `release` job that:
- Runs only on pushes to `main`
- Depends on the `check` job passing
- Runs `npx semantic-release` using the project's `.releaserc.json` config

#### Scenario: Release succeeds on feat commit
- **WHEN** a `feat:` commit is pushed to `main` and the check job passes
- **THEN** the release job SHALL create a new tag, CHANGELOG update, and GitHub release

#### Scenario: Release skips on non-conventional commits
- **WHEN** a commit without conventional prefix is pushed to `main`
- **THEN** the exit job SHALL complete without creating a release
