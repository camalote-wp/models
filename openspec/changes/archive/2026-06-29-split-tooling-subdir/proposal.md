## Why

The library's development tooling (phpstan, pint, pest, brain/monkey, semantic-release, captainhook) is currently mixed into the root `composer.json` and `package.json`, meaning every consumer's Composer must parse 7+ dev dependencies that have nothing to do with using the library. By moving all tooling into its own `tooling/` subdir with its own dependency manifests, the root `composer.json` becomes clean — `php: ^8.2` and nothing else — and consumers never see tooling deps at all.

Daily development uses proxy scripts in root `composer.json` that delegate to tooling via `--working-dir tooling`. Contributors run `composer check` from root and never need to `cd`.

## What Changes

- Create `tooling/` subdir with its own `composer.json`, `package.json`, and `composer.lock`
- Move all dev tooling dependencies from root `composer.json` to `tooling/composer.json`
- Move `package.json`, `package-lock.json`, `.releaserc.json` to `tooling/` (coupled via semantic-release)
- Move `phpstan.neon`, `phpunit.xml`, `patchwork.json`, `captainhook.json` to `tooling/`
- Strip root `composer.json` to have no `require-dev` (only `php: ^8.2`)
- Add proxy scripts to root `composer.json` that delegate to tooling via `--working-dir tooling`
- Update CI workflow with `working-directory: tooling` for tooling commands
- Update `.gitattributes` to exclude `tooling/` from Packagist dist
- Update `.devcontainer/setup.sh` to install tooling deps via `--working-dir tooling`
- Update README to document proxy scripts for daily use and `--working-dir tooling` for setup

## Capabilities

### Modified Capabilities
- `quality-tooling`: Static analysis, code style, CI workflow, and pre-commit hooks — tooling managed via `tooling/` subdir, invoked through root proxy scripts
- `semantic-release`: Release config (`package.json`, `.releaserc.json`) moves to `tooling/`, CI uses `working-directory: tooling`
- `packagist-distribution`: `.gitattributes` must exclude `tooling/` from dist archives

## Impact

- **Breaking**: Yes — contributors must run `composer install --working-dir tooling` for first-time setup. CI workflow paths change.
- **Root composer.json**: Loses all `require-dev` and `scripts` sections, gains proxy scripts for `test`/`stan`/`pint`/`check`
- **tooling/composer.json**: New file with all dev deps, autoload (src + tests), scripts, and allow-plugins
- **CI workflow**: Check and release jobs use `working-directory: tooling`
- **Packagist dist**: Even leaner — `tooling/` excluded entirely
- **Consumers**: Zero change in how they use the library; they never knew tooling existed
