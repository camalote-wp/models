## MODIFIED Requirements

### Requirement: CI release job runs on push to main
The project SHALL include a `release` job that runs only on pushes to `main`, depends on the `check` and `lint-packaging` jobs passing, and executes `npm ci` followed by `npx semantic-release` from the `tooling/` working directory.

#### Scenario: Release job runs on push to main
- **WHEN** a commit is pushed to `main` and the check and lint-packaging jobs pass
- **THEN** the release job SHALL run `npm ci` and `npx semantic-release` from the `tooling/` working directory

#### Scenario: Release job does not run on pull request
- **WHEN** a pull request is opened or updated
- **THEN** the `release` job SHALL NOT execute
