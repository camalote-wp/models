## MODIFIED Requirements

### Requirement: semantic-release configuration
The project SHALL include a `.releaserc.json` file at `tooling/.releaserc.json` that configures semantic-release with the conventionalcommits preset. The config SHALL use exactly these plugins: `@semantic-release/commit-analyzer`, `@semantic-release/release-notes-generator`, and `@semantic-release/github`. The `package.json` containing semantic-release as a dev dependency SHALL live at `tooling/package.json`.

#### Scenario: Config is valid
- **WHEN** `npx semantic-release --dry-run` is executed from the `tooling/` directory
- **THEN** it SHALL parse the config without error
