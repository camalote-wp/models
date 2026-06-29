## 1. Move Config Files to tooling/

- [x] 1.1 Move `phpstan.neon` → `tooling/phpstan.neon`
- [x] 1.2 Move `phpunit.xml` → `tooling/phpunit.xml`
- [x] 1.3 Move `patchwork.json` → `tooling/patchwork.json`
- [x] 1.4 Move `captainhook.json` → `tooling/captainhook.json`
- [x] 1.5 Move `package.json` → `tooling/package.json`
- [x] 1.6 Move `.releaserc.json` → `tooling/.releaserc.json`
- [x] 1.7 Move `package-lock.json` → `tooling/package-lock.json`

## 2. Create tooling/composer.json

- [x] 2.1 Create `tooling/composer.json` with all dev deps from root (pest, brain/monkey, yoast/phpunit-polyfills, phpstan, pint, phpstan-wordpress, captainhook-phar)
- [x] 2.2 Define Composer scripts (`test`, `stan`, `pint`, `check`) in `tooling/composer.json`
- [x] 2.3 Set `allow-plugins` in `tooling/composer.json` for pest-plugin and captainhook-phar
- [x] 2.4 Update `tooling/phpstan.neon` paths to `../src/` and `../tests/`

## 3. Strip Root composer.json

- [x] 3.1 Remove `require-dev` section from root `composer.json`
- [x] 3.2 Remove `scripts` section from root `composer.json`
- [x] 3.3 Remove `allow-plugins` section from root `composer.json`
- [x] 3.4 Keep `autoload` and `autoload-dev` in root (they scan `src/` and `tests/`, not tooling)

## 4. Add Proxy Scripts to Root composer.json

- [x] 4.1 Add proxy scripts in root `composer.json` that delegate to tooling via `--working-dir tooling` (test, stan, pint, check)
- [x] 4.2 Verify `composer check` from root proxies correctly to tooling

## 5. Update CI Workflow

- [x] 5.1 Use `--working-dir tooling` flag for composer commands in check job
- [x] 5.2 Use `--prefix tooling` flag for npm ci and npx semantic-release in release job
- [x] 5.3 lint-packaging job stays at root (checks .gitattributes, LICENSE)

## 6. Update .gitattributes

- [x] 6.1 Add `tooling/` to `.gitattributes` export-ignore
- [x] 6.2 Add `tooling/composer.lock` to `.gitattributes` export-ignore (covered by tooling/ wildcard)

## 7. Update captainhook.json

- [x] 7.1 Verify captainhook-phar plugin picks up `tooling/captainhook.json` when installed via `tooling/composer.json`
- [x] 7.2 Confirm action stays `"composer check"` (captainhook runs from tooling/ where its composer.json lives)
- [x] 7.3 Test: `composer install --working-dir tooling` triggers captainhook plugin and installs hooks to root `.git/hooks/`

## 8. Update .devcontainer/setup.sh

- [x] 8.1 Use `composer install --working-dir tooling` in setup.sh (tooling deps)
- [x] 8.2 Use `npm install --prefix tooling` in setup.sh (node deps)
- [x] 8.3 Keep root `composer install` in setup.sh (still needed for library autoload)

## 9. Update README

- [x] 9.1 Update Development section to document proxy scripts for daily use (`composer check` from root)
- [x] 9.2 Document `composer install --working-dir tooling` for first-time setup

## 10. Cleanup and Verify

- [x] 10.1 Delete root `composer.lock`
- [x] 10.2 Delete root `vendor/`
- [x] 10.3 Run `composer install --working-dir tooling` and verify all tools work
- [x] 10.4 Run `composer check` from root and verify green
- [x] 10.5 Add `tooling/vendor/` and `tooling/composer.lock` to `.gitignore`
- [x] 10.6 Verify `git status` shows no unexpected tracked files from tooling/
