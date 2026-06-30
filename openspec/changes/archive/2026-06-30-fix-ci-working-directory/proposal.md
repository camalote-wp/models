## Why

The CI release job fails because `npx semantic-release --prefix tooling` does not work as intended. The `--prefix` flag is not a valid npx option, so it gets silently ignored. npx then can't find semantic-release locally (it's in `tooling/node_modules`), fetches a fresh copy from npm registry, and runs it from the repo root. This causes cosmiconfig to fail to find `tooling/.releaserc.json`, falling back to default plugins including `@semantic-release/npm`, which then fails with `ENOPKG Missing package.json file`.

## What Changes

- Add `working-directory: tooling` to the release job in `.github/workflows/ci.yml`
- Remove `--prefix tooling` from npm/npx commands
- Update the ci and tooling specs to reflect the correct command

## Capabilities

### New Capabilities

_(none)_

### Modified Capabilities

- `ci`: Update release job requirement to use `working-directory: tooling` instead of `--prefix tooling`
- `tooling`: Fix the semantic-release scenario that references the non-functional `--prefix tooling` flag

## Impact

- `.github/workflows/ci.yml`: Only the release job changes
- `openspec/specs/ci/spec.md`: Release job requirement updated
- `openspec/specs/tooling/spec.md`: Scenario correction
- No runtime code changes
- No dependency changes
