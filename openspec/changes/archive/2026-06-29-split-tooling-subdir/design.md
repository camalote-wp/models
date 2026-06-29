## Context

The library `camalote-wp/models` currently has all development tooling (phpstan, pint, pest, brain/monkey, semantic-release, captainhook, devcontainer) declared in root-level manifests (`composer.json`, `package.json`) and config files. This means every consumer's Composer installation must parse 7+ dev dependencies that are irrelevant to using the library. The tooling also clutters the root directory, making it harder to distinguish library code from development infrastructure.

The project already has a clean separation of `src/` (library code) and `tests/` (test code). This change extends that separation to tooling.

## Goals / Non-Goals

**Goals:**
- Root `composer.json` contains only `php: ^8.2` — zero dev dependencies visible to consumers
- All tooling lives in `tooling/` with its own dependency graph
- CI workflow runs tooling commands from `tooling/` subdir
- Pre-commit hooks work from `tooling/` subdir
- Packagist dist excludes `tooling/` entirely
- Contributors run `cd tooling && composer install` once, then work normally

**Non-Goals:**
- Changing any source code in `src/`
- Changing test logic (only moving where config lives)
- Modifying semantic-release behavior or version scheme
- Adding or removing any tooling tools

## Decisions

### 1. Use `tooling/` subdir (not `dev/` or `.tooling/`)

**Choice:** `tooling/` at repo root.

**Rationale:** Visible, discoverable, conventional. A dot-directory (`.tooling`) hides it from casual contributors. `dev/` is ambiguous — could be confused with "dev environment" or "development branch." `tooling/` is explicit.

**Alternatives considered:**
- `.tooling/` — hidden, easy to forget, breaks `cd tab-completion` flow
- `dev/` — ambiguous naming
- `build/` — implies compiled artifacts, not dev tooling

### 2. Separate `composer.json` per subdir (not monorepo tooling like `monorepo-builder`)

**Choice:** Two independent `composer.json` files — root and `tooling/`.

**Rationale:** Composer natively supports this. Each directory with a `composer.json` is its own package with its own `vendor/`. No extra tooling needed. Consumers only ever see the root one.

**Alternatives considered:**
- `composer-monorepo-plugin` — adds complexity, another dependency, overkill for two packages
- `composer.workspace` — not a standard feature, requires plugin
- Single root `composer.json` with `provide`/`conflict` hacks — fragile, confusing

### 3. CI uses `working-directory: tooling` (not `cd tooling &&`)

**Choice:** GitHub Actions `working-directory` key on each step that needs tooling.

**Rationale:** Cleaner YAML, no shell chaining, each step's working directory is explicit. Avoids `cd tooling && ...` chains that break if one step forgets to cd back.

**Alternatives considered:**
- `cd tooling &&` prefix on every step — works but error-prone, easy to forget
- Custom GitHub Action — overkill
- Root-level wrapper scripts — adds indirection

### 4. `tooling/phpstan.neon` uses relative paths (`../src`, `../tests`)

**Choice:** Paths in `phpstan.neon` are relative to the `tooling/` subdir.

**Rationale:** phpstan.neon is read from the directory where phpstan runs. Since it lives in `tooling/`, paths must be `../src` and `../tests`. This is standard Composer behavior — binaries run from their install directory.

**Alternatives considered:**
- Absolute paths — breaks on different machines/CI
- Symlinks from `tooling/src` → `src` — adds git complexity, confusing

### 5. `tooling/captainhook.json` action uses `cd tooling && composer check`

**Choice:** The pre-commit hook action is `"cd tooling && composer check"`.

**Rationale:** captainhook runs from repo root by default. The action must navigate to tooling to find the right `composer.json` and `vendor/bin`.

**Alternatives considered:**
- Configure captainhook's `run_in` option — captainhook-phar doesn't support this cleanly
- Move hook logic to a script file — adds another file to maintain

## Risks / Trade-offs

- **[Contributor onboarding]** — New contributors must know to `cd tooling && composer install`. Mitigation: document in README Development section.
- **[CI breakage during migration]** — If any step forgets `working-directory: tooling`, it'll fail silently (command not found). Mitigation: CI check job catches this immediately on first push.
- **[captainhook config path]** — captainhook-phar looks for `captainhook.json` at repo root by default. After moving it to `tooling/`, the hook must be configured to find it. Mitigation: captainhook-phar supports `--config` flag or setting the config path; the `tooling/composer.json` post-install script must point to `tooling/captainhook.json`.
- **[Two composer installs]** — Contributors run `composer install` (root, minimal) AND `cd tooling && composer install` (tooling, full). Mitigation: document clearly; root install is near-instant (no dev deps).
- **[semantic-release working directory]** — semantic-release reads `.releaserc.json` from cwd. After moving to `tooling/`, the CI release job must `cd tooling` before running `npx semantic-release`. Mitigation: CI workflow updated accordingly.

## Migration Plan

1. Create `tooling/` directory
2. Move config files: `phpstan.neon`, `phpunit.xml`, `patchwork.json`, `captainhook.json`, `.devcontainer/`, `package.json`, `.releaserc.json`
3. Create `tooling/composer.json` with all dev deps + scripts
4. Update root `composer.json` to remove `require-dev` and scripts
5. Update CI workflow to use `working-directory: tooling`
6. Update `.gitattributes` to exclude `tooling/`
7. Update README Development section
8. Delete root `vendor/` and `composer.lock` (regenerate from tooling/)
9. Verify CI passes on a test push

## Open Questions

- Does captainhook-phar's composer plugin auto-install hooks when `composer install` runs in `tooling/`, or does it only trigger from root? May need to verify or configure manually.
- Should `tooling/package.json` keep the same `name` field or be renamed to reflect its purpose?
