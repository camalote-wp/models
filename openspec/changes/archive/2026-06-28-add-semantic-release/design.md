## Context

The project is a PHP library (`camalote-wp/models`) with:
- `composer.json` with no `"version"` field (Composer recommends deriving version from git tags)
- CI running via GitHub Actions on push + PR
- Conventional commit style already in use (`feat:`, `fix:`)
- No automated release process — tags are manual
- Devcontainer uses Node.js (for OpenSpec CLI), so `npm` is available in CI

The goal is automated semantic versioning + changelog + GitHub releases, all triggered by push to main.

## Goals / Non-Goals

**Goals:**
- `feat:` commits bump minor version, `fix:` commits bump patch version, `BREAKING CHANGE:` bumps major
- Auto-generated `CHANGELOG.md` committed back to the repo
- GitHub releases created with changelog body
- Git tags are the single source of truth for version
- Composer derives version from git tags (no `"version"` field in `composer.json`)

**Non-Goals:**
- No npm publish (library is PHP-only, not a node package)
- No release PRs or manual approval gate (project is small, trust CI)
- No backporting or multi-branch release management
- No changes to existing `quality-tooling` spec behavior (stan/pint/test unaffected)

## Decisions

### 1. semantic-release over custom bash script

**Choice:** Use `semantic-release` (npm package).
**Rationale:** Standard tool, handles edge cases (multiple commits in one push, changelog formatting, tag conflicts), actively maintained, well-documented. A custom bash script would be less robust and a maintenance burden over time.
**Trade-off:** Introduces a `package.json` with one devDependency. This is a tooling-only file, not a library dependency. It can live at repo root and is ignored by Composer.

### 2. Composer version derived from tags (no version field)

**Choice:** Do NOT add `"version"` to `composer.json`. Git tags are the single source of truth. semantic-release creates a git tag → Composer reads it during `composer install` and `composer show`.
**Rationale:** Composer explicitly recommends against hardcoding `"version"` in `composer.json` for projects using git. Tags are the canonical version source, and this avoids drift between the two.
**Implementation:** `scripts/sync-version.sh` only updates `package.json`. `validate-release.sh` only checks `package.json` against the tag.

### 3. Changelog committed back to main

**Choice:** semantic-release's `@semantic-release/changelog` plugin updates `CHANGELOG.md` and commits it.
**Rationale:** CHANGELOG should be reviewable in the repo, not just attached to a GitHub release.
**Implementation:** Use `git` plugin enabled (default). semantic-release will create a commit like `chore(release): 1.2.0 [skip ci]` containing the changelog update and tag.

### 4. Trigger on push to main (not PR merge via webhook)

**Choice:** CI job runs `semantic-release` on push to `main`.
**Rationale:** Simpler than setting up GitHub webhooks or GitHub App authentication. Since `main` is protected and CI already runs on push, this piggybacks on existing infrastructure.
**Trade-off:** If main receives multiple pushes rapidly, semantic-release handles this gracefully (reconciles tags).

### 5. Composer version derived from git tags

`composer.json` SHALL NOT include a `"version"` field. The semantic-release workflow creates git tags as the single source of truth. Composer natively reads git tags as version during install. The `scripts/sync-version.sh` only writes to `package.json` (tooling-only), not `composer.json`.

## Risks / Trade-offs

- **node/npm dependency for a PHP project:** `package.json` re-introduced even though we just nuked it. Mitigation: it's explicitly marked `"private": true`, only one devDependency, and clearly tooling-only.
- **semantic-release has many default plugins:** By default it tries to run `npm publish`. Mitigation: explicitly configure only the plugins we need in `.releaserc.json`.
- **Multiple rapid pushes to main:** Could theoretically create version churn. Mitigation: in practice this is a low-traffic library, and semantic-release is idempotent.
- **CHANGELOG.md bloat:** Over many releases CHANGELOG grows. Mitigation: standard concern, acceptable for now.
- **First release needs a starting tag:** semantic-release defaults to `1.0.0` if no tags exist. If you want to start at a different version, create an initial tag manually (`git tag v0.1.0`).
