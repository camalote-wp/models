## Why

The semantic-release implementation was intentionally streamlined during development — removing CHANGELOG.md generation, package.json version syncing, and post-release validation. However, the specs were never updated to match. The result: specs describe a workflow that doesn't exist, making them misleading for future contributors. This change corrects the specs to match the actual implemented workflow.

## What Changes

- **`openspec/specs/semantic-release/spec.md`** — simplify to match the streamlined 3-plugin workflow (commit-analyzer, release-notes-generator, github). Remove requirements for changelog, package.json sync, and post-release validation.
- **`openspec/specs/quality-tooling/spec.md`** — simplify the release job requirement to just "runs npx semantic-release", remove the verify-release step.
- **`scripts/sync-version.sh`** — delete (dead code, sync-version is not part of the workflow).
- **`scripts/validate-release.sh`** — delete (dead code, no verification step in CI).

## Capabilities

### New Capabilities
(none)

### Modified Capabilities
- `semantic-release`: remove requirements for CHANGELOG.md, sync-version.sh, exec/git plugins, post-release validation
- `quality-tooling`: simplify release job requirement, remove verify-release step requirement

## Impact

- Specs accurately reflect implemented CI workflow
- Dead scripts removed from the codebase
- No functional changes to CI itself (it's already working)
