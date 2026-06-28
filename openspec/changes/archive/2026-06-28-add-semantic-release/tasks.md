## 1. Setup

- [x] 1.1 Create `package.json` at repo root with `private: true` and `semantic-release` as a devDependency
- [x] 1.2 Run `npm install` and commit `package.json` and `package-lock.json`

## 2. Semantic Release Configuration

- [x] 2.1 Create `.releaserc.json` with: branch `main`, plugins (commit-analyzer with preset `conventionalcommits`, release-notes-generator, changelog, exec, git, github), npmPublish false
- [x] 2.2 Add `@semantic-release/exec` plugin to `package.json` devDependencies
- [x] 2.3 Run `npx semantic-release --dry-run` to verify config is valid

## 3. Version Sync Script

- [x] 3.1 Create `scripts/sync-version.sh` that validates semver, backs up originals, writes version to `package.json` only (composer.json has no version field)
- [x] 3.2 Add `prepareCmd` to `.releaserc.json` that runs `scripts/sync-version.sh ${nextRelease.version}` (done in task 2.1)
- [x] 3.3 Add `package.json` to the `@semantic-release/git` `assets` (done in task 2.1)
- [x] 3.4 Create `scripts/validate-release.sh` that verifies `package.json` version matches the git tag, failing the build if they drift

## 4. Composer Version

- [x] 4.1 Ensure `composer.json` has no `"version"` field (version derived from git tags) — already correct
- [x] 4.2 Update `composer.lock` by running `composer update --lock`

## 5. CI Integration

- [x] 5.1 Add `release` job to `.github/workflows/ci.yml` that runs on push to `main`, depends on `check` job, runs `npx semantic-release`
- [x] 5.2 Ensure the release job has `contents: write` permission for GitHub releases
- [x] 5.3 Add a `verify-release` step after `semantic-release` that checks out the latest tag, runs `scripts/validate-release.sh`, and confirms all version sources align

## 6. Initial Tag

- [x] 6.1 Create starting git tag at `v0.1.0` and push to remote (user will do this)
