#!/bin/sh
set -e

VERSION="$1"

if [ -z "$VERSION" ]; then
  echo "Usage: sync-version.sh <version>"
  exit 1
fi

case "$VERSION" in
  0.0.0) ;;
  [0-9]*.[0-9]*.[0-9]*) ;;
  *) echo "Invalid semver: $VERSION"; exit 1 ;;
esac

if ! command -v jq >/dev/null 2>&1; then
  echo "Error: jq is required but not installed"
  exit 1
fi

WORKSPACE="$(cd "$(dirname "$0")/.." && pwd)"
FILE="$WORKSPACE/package.json"

if [ ! -f "$FILE" ]; then
  echo "Skipping package.json (not found)"
  exit 0
fi

cp "$FILE" "$FILE.bak"
jq --arg v "$VERSION" '.version = $v' "$FILE" > "$FILE.tmp" && mv "$FILE.tmp" "$FILE"
rm -f "$FILE.bak"

echo "Synced version $VERSION to package.json"
