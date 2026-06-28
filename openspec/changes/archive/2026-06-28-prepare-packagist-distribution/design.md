## Context

The library `camalote-wp/models` is a PHP Composer package with a functioning CI pipeline (`.github/workflows/ci.yml`) and semantic-release for automated tagging. The `composer.json` is properly structured with PSR-4 autoload. What's missing is the packaging hygiene required for a clean Packagist distribution: no `.gitattributes` to filter dist contents, no `LICENSE` file, and no CI validation of either.

## Goals / Non-Goals

**Goals:**
- Produce lean Packagist dist archives containing only what consumers need to use the library
- Include proper license text in the distribution
- Validate packaging files in CI before release

**Non-Goals:**
- Automating the Packagist submission (manual one-time step)
- Building or attaching archive artifacts to GitHub releases (Packagist handles this)
- Changing any source code or the existing `composer.json`

## Decisions

### 1. Use `.gitattributes` over `composer.json` `archive` key

**Choice:** Add a `.gitattributes` file with `export-ignore` directives.

**Rationale:** `.gitattributes` is the idiomatic PHP ecosystem approach. It works at the Git level, meaning it applies to `git archive`, GitHub zip downloads, and Packagist uniformly. The `archive` key in `composer.json` is Composer-native but less visible to contributors.

**Alternatives considered:**
- `composer.json` `archive.exclude` — works but less discoverable, more common in Node/npm ecosystem
- Both simultaneously — redundant, adds maintenance surface for no gain

### 2. CI validation via a dedicated lint step

**Choice:** Add a new job `lint-packaging` that checks `.gitattributes` exists and contains `export-ignore` entries. Run it before the `release` job.

**Rationaling:** A simple `grep` check is sufficient. This catches accidental removal or corruption of `.gitattributes` before it reaches a tagged release. The check is fast and has zero dependencies.

**Alternatives considered:**
- Validate in the existing `check` job alongside `composer check` — rejected because `check` runs on every push/PR, but packaging validation only matters pre-release. A separate job keeps concerns separated and allows the release gate to depend on it.

### 3. LICENSE file content

**Choice:** GPL-2.0 full text, matching the `composer.json` `"license": "GPL-2.0-or-later"` declaration.

**Rationale:** The `composer.json` already declares the license. The `LICENSE` file provides the full legal text that consumers and automated license scanners expect. GPL-2.0-or-later text is canonical from gnu.org.

## Risks / Trade-offs

- **[Over-exclusion in .gitattributes]** → If a file needed by consumers is accidentally `export-ignore`d, downstream installs break. Mitigation: the spec explicitly lists only dev-only paths; `src/` and `vendor/` are never excluded.
- **[LICENSE file drift]** → If the license changes in `composer.json` but `LICENSE` isn't updated, they'll disagree. Mitigation: CI could validate the license declaration matches, but this is low-risk for a single-author library.
