## Why

The library (`camalote-wp/models`) is ready for distribution via Packagist, but currently lacks the metadata and hygiene files required for a clean, professional package. Without a `.gitattributes` file, every dev file (tests, CI config, editor tooling) ends up in the Packagist dist archive. Without a `LICENSE` file, the GPL-2.0 declaration in `composer.json` has no corresponding legal text.

## What Changes

- Add a `.gitattributes` file with `export-ignore` rules to exclude development-only files from the Packagist dist archive
- Add a `LICENSE` file containing the GPL-2.0-or-later full text
- Add a CI lint step that validates `.gitattributes` is present and well-formed before release

## Capabilities

### New Capabilities
- `packagist-distribution`: Prepare the library for clean distribution via Packagist by controlling dist-embedded files and ensuring license compliance

### Modified Capabilities

## Impact

- **Files added**: `.gitattributes`, `LICENSE`
- **CI workflow**: New lint step added to the existing `check` job in `.github/workflows/ci.yml`
- **No code changes**: `src/` is untouched; this is purely packaging/metadata
- **Downstream consumers**: Packagist dist archives will be lean — only `src/`, `composer.json`, and `LICENSE`
