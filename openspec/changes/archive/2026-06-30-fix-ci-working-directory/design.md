## Context

The CI workflow at `.github/workflows/ci.yml` runs tooling commands from the repo root using various flags (`--working-dir tooling`, `--prefix tooling`). The release job uses `npx semantic-release --prefix tooling`, which fails because:

1. `--prefix` is not a valid npx option — it gets passed to semantic-release which silently ignores it
2. npx can't find the locally installed semantic-release (in `tooling/node_modules`), so it fetches a fresh copy from npm
3. The fresh copy runs from repo root, where cosmiconfig can't find `tooling/.releaserc.json`
4. Falls back to default plugins including `@semantic-release/npm`, which fails with `ENOPKG`

The root cause: semantic-release uses cosmiconfig which searches **upward** from `process.cwd()` for config files. There is no CLI flag to specify a config path. The only way to make it find `tooling/.releaserc.json` is to change CWD to `tooling/` before execution.

## Goals / Non-Goals

**Goals:**
- Fix the CI release job so semantic-release finds its config and runs correctly
- Normalize CI jobs to use `working-directory: tooling` consistently for all tooling commands
- Update the spec to reflect reality

**Non-Goals:**
- Changing the tooling subdir structure
- Moving config files to root
- Modifying semantic-release behavior

## Decisions

### Decision 1: Use `working-directory: tooling` for the release job

**Why:** GitHub Actions `working-directory` sets CWD for all `run` steps in a job. This is the only reliable way to make cosmiconfig find `tooling/.releaserc.json`.

**Alternatives considered:**
- `cd tooling && npx semantic-release` — works but inconsistent with GitHub Actions patterns
- `npm exec --prefix tooling -- semantic-release` — npm exec's `--prefix` affects package resolution, not CWD. Still fails.
- Moving `.releaserc.json` to root — violates the tooling spec
- Adding `--plugins` inline to semantic-release — duplicates config, hard to maintain

**Chosen:** `defaults.run.working-directory: tooling` at job level for the release job.

### Decision 2: Apply `working-directory: tooling` to check job as well

**Why:** Consistency. The `check` job currently uses `--working-dir tooling` (Composer's flag). By using `working-directory: tooling` and dropping the flag, both jobs follow the same pattern: CWD-based tooling execution.

**Risk:** Root `composer.json` proxies to `tooling/` via `--working-dir tooling`. Running `composer check` from `tooling/` directly calls the actual scripts without proxying — same result, one less layer.

### Decision 3: Update tooling spec scenario

**Why:** The spec at `openspec/specs/tooling/spec.md:76` asserts `npx semantic-release --dry-run --prefix tooling` works. This is factually incorrect — the `--prefix` flag doesn't exist in npx. The scenario must be updated to use `npx semantic-release --dry-run` (run from tooling directory).

## Risks / Trade-offs

- **`actions/checkout` and `actions/setup-node` don't honor `working-directory`** → These are `uses` steps, not `run` steps, so they execute at repo root regardless. This is correct — checkout must run at root, and setup-node installs globally. No mitigation needed.
- **Spec update required** → The tooling spec explicitly references the broken command. Must update to avoid drift. Mitigation: included in this change.
