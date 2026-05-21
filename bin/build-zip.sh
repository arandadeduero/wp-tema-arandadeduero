#!/usr/bin/env bash
set -euo pipefail

THEME_SLUG="wp-tema-arandadeduero"
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

# Determine version: prefer git tag, fall back to short commit hash
if git -C "$REPO_ROOT" describe --exact-match --tags HEAD 2>/dev/null; then
    VERSION="$(git -C "$REPO_ROOT" describe --exact-match --tags HEAD)"
else
    VERSION="commit$(git -C "$REPO_ROOT" rev-parse --short HEAD)"
fi

ZIP_NAME="${THEME_SLUG}-${VERSION}.zip"
DIST_DIR="${REPO_ROOT}/dist"

mkdir -p "$DIST_DIR"

echo "Building ${ZIP_NAME}..."

git -C "$REPO_ROOT" archive \
    --format=zip \
    --prefix="${THEME_SLUG}/" \
    HEAD \
    -o "${DIST_DIR}/${ZIP_NAME}"

echo "Done: dist/${ZIP_NAME}"
