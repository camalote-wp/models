## Why

Releases are currently manual — requires remembering to tag, write changelogs, and create GitHub releases. For a small project, this overhead means releases happen irregularly or not at all. The project already uses conventional commits (`feat:`, `fix:`) and has a CI pipeline, so all the inputs for automated semantic versioning are already in place.

## What Changes

- Add `semantic-release` as a dev-only tool (via `package.json` in devcontainer/CI only, no runtime dependency).
- Add `.releaserc.json` config for semantic-release (commits-analyzer, changelog, composer tag-only plugin, GitHub release).
- Add a CI job that runs `semantic-release` on push to `main` — auto-tags, generates CHANGELOG.md, creates GitHub release.
- Remove `"version"` from `composer.json` so Composer infers version from git tags (the source of truth semantic-release manages).
- Add `CHANGELOG.md` (auto-maintained by semantic-release).

## Capabilities

### New Capabilities
- `semantic-release`: Automated versioning, changelog generation, and GitHub releases driven by conventional commits.

### Modified Capabilities
- `quality-tooling`: CI workflow extended with a release job; composer.json no longer carries an explicit version field.

## Impact

- `composer.json`: `"version"` field removed (Composer reads git tags natively).
- `package.json`: re-added with only `semantic-release` as a devDependency (tooling only, not a library dependency).
- `.github/workflows/ci.yml`: new release job added.
- New file: `.releaserc.json` (semantic-release config).
- New file: `CHANGELOG.md` (auto-generated, committed back to main).
- No changes to `src/`, `tests/`, or existing spec behavior.
