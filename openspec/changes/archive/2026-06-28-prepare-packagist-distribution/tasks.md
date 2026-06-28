## 1. Packaging Metadata

- [x] 1.1 Create `.gitattributes` at repo root with `export-ignore` for: `.devcontainer/`, `.github/`, `.kilocode/`, `.releaserc.json`, `composer.lock`, `package.json`, `package-lock.json`, `node_modules/`, `phpunit.xml`, `phpstan.neon`, `captainhook.json`, `patchwork.json`
- [x] 1.2 Add `LICENSE` file at repo root containing the GPL-2.0-or-later full text

## 2. CI Validation

- [x] 2.1 Add a `lint-packaging` job to `.github/workflows/ci.yml` that checks `.gitattributes` exists and contains at least one `export-ignore` line
- [x] 2.2 Make the `release` job depend on `lint-packaging` so packaging validation gates releases

## 3. Verification

- [x] 3.1 Run `composer check` locally to confirm no regressions
- [x] 3.2 Verify `.gitattributes` syntax with `git check-attr` (sanity check no typos)
- [x] 3.3 Confirm `LICENSE` file is not in `.gitignore` or `.gitattributes` export-ignore
