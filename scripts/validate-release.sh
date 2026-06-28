#!/bin/sh
set -e

WORKSPACE="$(cd "$(dirname "$0")/.." && pwd)"

TAG=$(git -C "$WORKSPACE" describe --tags --abbrev=0 2>/dev/null)
if [ -z "$TAG" ]; then
  echo "Error: no git tag found"
  exit 1
fi

TAG_VERSION="${TAG#v}"

PACKAGE_VERSION=$(jq -r '.version' "$WORKSPACE/package.json" 2>/dev/null)

echo "Tag:              $TAG_VERSION"
echo "package.json:     $PACKAGE_VERSION"

if [ "$TAG_VERSION" != "$PACKAGE_VERSION" ]; then
  echo "MISMATCH: tag ($TAG_VERSION) != package.json ($PACKAGE_VERSION)"
  exit 1
fi

echo "All version sources aligned at $TAG_VERSION"
