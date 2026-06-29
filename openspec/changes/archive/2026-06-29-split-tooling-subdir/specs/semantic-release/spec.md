# Semantic Release

Automated versioning and GitHub releases driven by conventional commits.

## ADDED Requirements

### Requirement: CI triggers release on push to main
The CI workflow SHALL include a `release` job that runs only on push to `main` (not on PRs) and executes `npx semantic-release`.

#### Scenario: Release runs on push to main
- **WHEN** a `feat:` commit is pushed to `main`
- **THEN** the release job SHALL create a new minor version tag and GitHub release

#### Scenario: Release skips non-release pushes
- **WHEN** a commit without `feat:`, `fix:`, or `BREAKING CHANGE:` is pushed to `main`
- **THEN** the release job SHALL complete without creating a tag or release

### Requirement: GitHub release created automatically
Each semantic release SHALL create a GitHub release matching the git tag, with the release notes as the release body.

#### Scenario: GitHub release matches tag
- **WHEN** semantic-release creates tag `v0.2.0`
- **THEN** a GitHub release SHALL exist at `v0.2.0` with release notes as body

### Requirement: Version derived from git tags
The project SHALL NOT include an explicit `"version"` field in `composer.json`. Composer SHALL derive the version from the latest git tag during install.

#### Scenario: Composer resolves version from tag
- **WHEN** `composer show camalote-wp/models` is run on a repository with tag `v0.2.0`
- **THEN** Composer SHALL report the version as `0.2.0`

#### Scenario: Dev installs resolve to `dev-main`
- **WHEN** a developer requires the package with `dev-main`
- **THEN** Composer SHALL resolve the version as `9999999-dev` (standard Composer behavior for branch installs)

## MODIFIED Requirements

### Requirement: semantic-release configuration
~~The project SHALL include a `.releaserc.json` file at the repo root that configures semantic-release with the conventionalcommits preset. The config SHALL use exactly these plugins: `@semantic-release/commit-analyzer`, `@semantic-release/release-notes-generator`, and `@semantic-release/github`.~~
The project SHALL include a `.releaserc.json` file in the `tooling/` subdir that configures semantic-release with the conventionalcommits preset. The config SHALL use exactly these plugins: `@semantic-release/commit-analyzer`, `@semantic-release/release-notes-generator`, and `@semantic-release/github`. The `package.json` containing semantic-release as a dev dependency SHALL also live in `tooling/`.

#### Scenario: Config is valid
- **WHEN** `composer check --working-dir tooling` is executed (which runs semantic-release --dry-run via tooling scripts)
- **THEN** it SHALL parse the config without error

#### Scenario: CI runs semantic-release from tooling
- **WHEN** the release job executes in CI
- **THEN** it SHALL run `npm ci --prefix tooling` and `npx semantic-release --prefix tooling`
