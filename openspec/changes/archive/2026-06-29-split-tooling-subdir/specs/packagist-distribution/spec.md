# Packagist Distribution

This capability covers the packaging hygiene required for distributing the library via Packagist: keeping dist archives lean, including proper license metadata, and validating both in CI.

## Requirements

### Requirement: LICENSE file present
A `LICENSE` file containing the full GPL-2.0-or-later text SHALL exist at the repository root.

#### Scenario: Consumer inspects license
- **WHEN** a consumer opens the library root
- **THEN** a `LICENSE` file SHALL be present with the GPL-2.0 full text

#### Scenario: Packagist dist includes LICENSE
- **WHEN** Packagist builds the dist archive
- **THEN** the `LICENSE` file SHALL be included in the archive

### Requirement: CI validates packaging files
The CI pipeline SHALL validate that `.gitattributes` exists and contains `export-ignore` rules before allowing a release.

#### Scenario: CI runs on pull request
- **WHEN** a pull request targets `main`
- **THEN** the CI SHALL verify `.gitattributes` exists and contains at least one `export-ignore` entry

#### Scenario: CI runs on push to main
- **WHEN** code is pushed to `main`
- **THEN** the CI SHALL verify `.gitattributes` exists and contains at least one `export-ignore` entry before the release job runs

## MODIFIED Requirements

### Requirement: Dist archive excludes development files
~~The `.gitattributes` file SHALL use `export-ignore` to prevent development-only files and directories from appearing in the Packagist-generated dist archive.~~
The `.gitattributes` file SHALL use `export-ignore` to prevent development-only files and directories from appearing in the Packagist-generated dist archive, including the entire `tooling/` subdir.

#### Scenario: Packagist generates dist from tag
- **WHEN** Packagist builds the dist archive from a tagged release
- **THEN** the archive SHALL NOT contain `.devcontainer/`, `.github/`, `.kilocode/`, `tests/`, `node_modules/`, `src/` test fixtures, dev tooling config files, or the `tooling/` directory

#### Scenario: Source repo remains complete
- **WHEN** a developer clones the repository
- **THEN** all files including dev tooling, tests, CI config, and `tooling/` SHALL be present in the working tree
