# Semantic Release

Automated versioning, changelog, and GitHub releases driven by conventional commits.

## ADDED Requirements

### Requirement: semantic-release configuration
The project SHALL include a `.releaserc.json` file at the repo root that configures semantic-release to use conventional commits, generate changelog, and create GitHub releases. The config SHALL specify:
- Branch: `main`
- Plugins: commit-analyzer, release-notes-generator, changelog, exec, git, github
- No npm publish (library is PHP-only)
- `package.json` listed in `@semantic-release/git` assets (to sync its version field)
- `composer.json` SHALL NOT be listed in git assets (it has no version field)

#### Scenario: Config is valid
- **WHEN** `semantic-release --dry-run` is executed
- **THEN** it SHALL parse the config without error and output the next version (or null if no release-worthy commits)

### Requirement: CI triggers release on push to main
The CI workflow SHALL include a `release` job that runs only on push to `main` (not on PRs) and executes `npx semantic-release`.

#### Scenario: Release runs on push to main
- **WHEN** a `feat:` commit is pushed to `main`
- **THEN** the release job SHALL create a new minor version tag and GitHub release

#### Scenario: Release skips non-release pushes
- **WHEN** a commit without `feat:`, `fix:`, or `BREAKING CHANGE:` is pushed to `main`
- **THEN** the release job SHALL complete without creating a tag or release

### Requirement: CHANGELOG.md is auto-generated and committed
The project SHALL include a `CHANGELOG.md` at repo root, created and maintained by semantic-release. On the first release, semantic-release SHALL create this file. On subsequent releases, it SHALL prepend new entries. The CHANGELOG.md SHALL be committed back to the main branch as part of the release.

#### Scenario: Changelog created on first release
- **WHEN** the first `feat:` or `fix:` commit triggers a release
- **THEN** CHANGELOG.md SHALL be created with the features formatted as bullet points

#### Scenario: Changelog updated on subsequent releases
- **WHEN** additional `feat:` or `fix:` commits are merged after the first release
- **THEN** CHANGELOG.md SHALL be updated with new entries prepended under the new version

### Requirement: GitHub release created automatically
Each semantic release SHALL create a GitHub release matching the git tag, with the changelog entry as the release body.

#### Scenario: GitHub release matches tag
- **WHEN** semantic-release creates tag `v1.2.0`
- **THEN** a GitHub release SHALL exist at `v1.2.0` with the corresponding changelog text as body

### Requirement: package.json version synced on release
`scripts/sync-version.sh` SHALL write the release version to `package.json` during the semantic-release `prepare` step. `composer.json` SHALL NOT be modified by the sync script (it derives version from git tags).

#### Scenario: package.json version matches the release tag
- **WHEN** semantic-release creates tag `v1.2.0`
- **THEN** `package.json` SHALL contain `"version": "1.2.0"`

### Requirement: Version derived from git tags (no version field in composer.json)
`composer.json` SHALL NOT include an explicit `"version"` field. Composer SHALL derive the version from the latest git tag during install.

#### Scenario: Composer resolves version from tag
- **WHEN** `composer show camalote-wp/models` is run on a repository with tag `v1.2.0`
- **THEN** Composer SHALL report the version as `1.2.0`

#### Scenario: Dev installs resolve to `dev-main`
- **WHEN** a developer requires the package with `dev-main`
- **THEN** Composer SHALL resolve the version as `9999999-dev` (standard Composer behavior for branch installs)
