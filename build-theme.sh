#!/bin/bash

# Build production-ready theme zip
# Usage: ./build-theme.sh

THEME_NAME="increative"
VERSION=$(grep -o '"version": "[^"]*' composer.json 2>/dev/null | cut -d'"' -f4 || echo "1.0.0")
BUILD_DIR="dist"
ZIP_NAME="${THEME_NAME}-${VERSION}.zip"

echo "🚀 Building ${THEME_NAME} theme v${VERSION}..."

# Clean previous builds
rm -rf "$BUILD_DIR"
rm -f "$ZIP_NAME"

# Create build directory
mkdir -p "$BUILD_DIR/$THEME_NAME"

# Build assets
echo "📦 Building assets..."
npm run build

# Copy required files
echo "📋 Copying files..."

# Core files
cp -r app "$BUILD_DIR/$THEME_NAME/"
cp -r resources "$BUILD_DIR/$THEME_NAME/"
cp -r public "$BUILD_DIR/$THEME_NAME/"
cp -r vendor "$BUILD_DIR/$THEME_NAME/"

# Storage directory (required for Acorn)
mkdir -p "$BUILD_DIR/$THEME_NAME/storage/framework/cache"
mkdir -p "$BUILD_DIR/$THEME_NAME/storage/framework/views"
mkdir -p "$BUILD_DIR/$THEME_NAME/storage/framework/sessions"
mkdir -p "$BUILD_DIR/$THEME_NAME/storage/logs"
touch "$BUILD_DIR/$THEME_NAME/storage/framework/cache/.gitkeep"
touch "$BUILD_DIR/$THEME_NAME/storage/framework/views/.gitkeep"
touch "$BUILD_DIR/$THEME_NAME/storage/logs/.gitkeep"

# Root files
cp functions.php "$BUILD_DIR/$THEME_NAME/"
cp index.php "$BUILD_DIR/$THEME_NAME/"
cp style.css "$BUILD_DIR/$THEME_NAME/"
cp theme.json "$BUILD_DIR/$THEME_NAME/"
cp screenshot.png "$BUILD_DIR/$THEME_NAME/" 2>/dev/null || true
cp LICENSE.md "$BUILD_DIR/$THEME_NAME/" 2>/dev/null || true
cp README.md "$BUILD_DIR/$THEME_NAME/" 2>/dev/null || true
cp composer.json "$BUILD_DIR/$THEME_NAME/"
cp composer.lock "$BUILD_DIR/$THEME_NAME/"

# Remove development files from vendor
echo "🧹 Cleaning up..."
find "$BUILD_DIR/$THEME_NAME/vendor" -name "*.md" -delete 2>/dev/null || true
find "$BUILD_DIR/$THEME_NAME/vendor" -name "phpunit*" -type d -exec rm -rf {} + 2>/dev/null || true
find "$BUILD_DIR/$THEME_NAME/vendor" -name "tests" -type d -exec rm -rf {} + 2>/dev/null || true
find "$BUILD_DIR/$THEME_NAME/vendor" -name ".git*" -exec rm -rf {} + 2>/dev/null || true

# Create zip
echo "🗜 Creating zip..."
cd "$BUILD_DIR"
zip -r "../$ZIP_NAME" "$THEME_NAME" -x "*.DS_Store" -x "*/.git/*"
cd ..

# Get size
ZIP_SIZE=$(du -h "$ZIP_NAME" | cut -f1)

# Cleanup
rm -rf "$BUILD_DIR"

echo ""
echo "✅ Build complete!"
echo "📁 File: $ZIP_NAME"
echo "📏 Size: $ZIP_SIZE"
echo ""
echo "Upload this zip file to WordPress via Appearance → Themes → Add New → Upload Theme"
echo ""
echo "⚠️  IMPORTANT: After activating the theme, you may need to:"
echo "   1. Ensure storage/ folder has write permissions (chmod -R 755 storage)"
echo "   2. Run 'wp acorn optimize' if you have WP-CLI access"
