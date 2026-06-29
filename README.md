## Installation

```bash
composer require camalote-wp/models
```

## Development

First-time setup:

```bash
composer install --working-dir tooling   # Install PHP dev tooling (phpstan, pint, pest, captainhook)
npm install --prefix tooling             # Install semantic-release
```

Daily use (from repo root):

```bash
composer test          # Run tests (proxies to tooling/)
composer stan          # Run static analysis (proxies to tooling/)
composer pint          # Check code style (proxies to tooling/)
composer check         # Run all three (proxies to tooling/)
```
