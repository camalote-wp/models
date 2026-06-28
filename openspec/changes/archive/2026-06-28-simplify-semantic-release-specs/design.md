## Context

The project implemented semantic-release with a streamlined 3-plugin workflow:
- `@semantic-release/commit-analyzer` — determines version bump from conventional commits
- `@semantic-release/release-notes-generator` — generates release notes from commits
- `@semantic-release/github` — creates git tag + GitHub release

The `.releaserc.json` intentionally excludes `@semantic-release/changelog`, `@semantic-release/exec`, and `@semantic-release/git` plugins because they add complexity without value for this small PHP library.

However, the specs (`openspec/specs/semantic-release/spec.md` and `openspec/specs/quality-tooling/spec.md`) still describe the full workflow with all plugins, CHANGELOG.md generation, sync-version.sh, validate-release.sh, etc. The specs are stale.

## Goals / Non-Goals

**Goals:**
- Update `semantic-release` spec to match the streamlined 3-plugin workflow
- Update `quality-tooling` spec to simplify the release job requirement
- Remove references to dead scripts (sync-version.sh, validate-release.sh)

**Non-Goals:**
- No changes to the actual CI workflow (`.github/workflows/ci.yml` is already correct)
- No changes to `.releaserc.json` (already correct)
- No changes to semantic-release tooling or configuration

## Decisions

### 1. Delete dead scripts rather than leave them as "future use"

**Choice:** Delete `scripts/sync-version.sh` and `scripts/validate-release.sh`.
**Rationale:** They were created for plugins that are intentionally excluded from the workflow. Keeping them as dead code creates confusion about whether they should be used. The semantic-release config is the source of truth for what runs, and it doesn't reference these scripts.
**Alternatives considered:** Leaving them "just in case" — rejected, they document a workflow we explicitly chose not to build.

### 2. Simplify spec requirements rather than mark as "deprecated"

**Choice:** Rewrite the requirements cleanly rather than using REMOVED headers for individual requirements.
**Rationale:** This is a spec cleanup, not a feature removal. The cleaner approach makes the spec readable without archaeology.

## Risks / Trade-offs

- **Loss of CHANGELOG.md in repo:** The GitHub release page becomes the only changelog. Mitigation: this is standard for small libraries and the release notes are still accessible via GitHub's UI and API.
- **Future contributors might want the fuller workflow:** Mitigation: `.releaserc.json` is where plugins are configured; re-adding them later is a config change, not an architecture change.
