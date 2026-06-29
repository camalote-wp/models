## Requirements

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

### Requirement: Dist archive excludes development files
The `.gitattributes` file SHALL use `export-ignore` to prevent development-only files and directories from appearing in the Packagist-generated dist archive, including the entire `tooling/` subdir.

#### Scenario: Packagist generates dist from tag
- **WHEN** Packagist builds the dist archive from a tagged release
- **THEN** the archive SHALL NOT contain `.devcontainer/`, `.github/`, `.kilocode/`, `tests/`, `node_modules/`, `src/` test fixtures, dev tooling config files, or the `tooling/` directory

#### Scenario: Source repo remains complete
- **WHEN** a developer clones the repository
- **THEN** all files including dev tooling, tests, CI config, and `tooling/` SHALL be present in the working tree

### Requirement: LICENSE file present
A `LICENSE` file containing the full GPL-2.0-or-later text SHALL exist at the repository root.

#### Scenario: Consumer inspects license
- **WHEN** a consumer opens the library root
- **THEN** a `LICENSE` file SHALL be present with the GPL-2.0 full text

#### Scenario: Packagist dist includes LICENSE
- **WHEN** Packagist builds the dist archive
- **THEN** the `LICENSE` file SHALL be included in the archive
