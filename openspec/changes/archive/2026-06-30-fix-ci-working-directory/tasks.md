## 1. Fix release job

- [x] 1.1 Add `defaults.run.working-directory: tooling` to the `release` job
- [x] 1.2 Remove `--prefix tooling` from `npm ci` and `npx semantic-release` commands

## 2. Verify

- [x] 2.1 Run `npx semantic-release --dry-run` from `tooling/` directory to verify the release job works
