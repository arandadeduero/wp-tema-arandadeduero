# Release & Deployment Guide

How to build and deploy the Aranda de Duero WordPress theme to your server.

## Quick Release Build

```bash
npm run release
```

This command will:

1. ✅ Build all assets (SCSS → CSS, minify JavaScript)
2. ✅ Run all linters and syntax checks
3. ✅ Create a clean WordPress theme package
4. ✅ Compress everything into a ZIP file
5. ✅ Output: `dist/aranda-de-duero-2.0.0.zip`

The ZIP file contains **only** the necessary WordPress theme files, excluding:

-   Development dependencies (node_modules, vendor)
-   Source files (SCSS, source maps)
-   Configuration files (.eslintrc.json, .prettierrc, etc.)
-   Development documentation (BUILD_SETUP.md, DEVELOPMENT.md)
-   Git files (.git, .github)

## Files Included in Release

### Core WordPress Files

```
aranda-de-duero/
├── 404.php                    # 404 page template
├── archive.php                # Archive page template
├── comments.php               # Comments template
├── footer.php                 # Footer template
├── functions.php              # Theme functions
├── header.php                 # Header template
├── index.php                  # Main template (required)
├── page.php                   # Page template
├── search.php                 # Search template
├── searchform.php             # Search form
├── sidebar.php                # Sidebar template
├── single.php                 # Single post template
├── style.css                  # Main stylesheet (required)
├── style-rtl.css              # RTL stylesheet
```

### Custom Templates (Pages)

```
├── page-agenda.php
├── page-ayuntamiento.php
├── page-bandos.php
├── page-home.php
├── page-legales.php
├── page-multimedia.php
├── page-news.php
├── page-sinsidebars.php
├── page-tramite.php
├── page-villa.php
├── page-webmap.php
```

### Custom Post Type Templates

```
├── single-mc-events.php
├── single-tramite.php
```

### Taxonomy Templates

```
├── taxonomy-concejalia-*.php  (3 files)
├── taxonomy-tema-*.php         (15 files)
```

### Assets

```
├── css/
│   └── aranda-de-duero.css     # Compiled & minified (production-ready)
├── js/
│   ├── aranda-de-duero.js      # Minified (production-ready)
│   ├── customizer.js           # Minified
│   ├── navigation.js           # Minified
│   └── (no jQuery!)
├── images/                     # Theme images
└── languages/                  # Translation files
```

### Supporting Files

```
├── inc/                        # PHP includes
│   ├── custom-header.php
│   ├── customizer.php
│   ├── jetpack.php
│   ├── template-functions.php
│   └── template-tags.php
├── template-parts/             # Reusable components (8 files)
├── LICENSE                     # GPL 2.0 license
└── README.md                   # Theme documentation
```

## Installation Steps

### Option 1: Using WordPress Admin (Recommended for Non-Developers)

1. Download the ZIP file: `dist/aranda-de-duero-2.0.0.zip`
2. Go to **Appearance → Themes** in WordPress Admin
3. Click **Add New Theme**
4. Click **Upload Theme**
5. Select the ZIP file and click **Install Now**
6. Click **Activate** to use the theme

### Option 2: Using FTP/SFTP

1. Build the release: `npm run release`
2. Download `dist/aranda-de-duero-2.0.0.zip`
3. Extract the ZIP file
4. Upload the `aranda-de-duero/` folder to `/wp-content/themes/` on your server
5. Go to **Appearance → Themes** and activate the theme

### Option 3: Using WP-CLI (Advanced)

```bash
# Download and activate in one command
wp theme install /path/to/dist/aranda-de-duero-2.0.0.zip --activate
```

## Pre-Release Checklist

Before building a release, verify everything:

```bash
# 1. Check code quality
npm run lint
npm run lint:php

# 2. Verify build succeeds
npm run build

# 3. Test in development
npm start
# Visit http://localhost:3000 and test functionality

# 4. Run the release build
npm run release
```

## Release Build Process

### What `npm run release` Does

1. **Builds Assets**

    ```bash
    npm run build
    ```

    - Compiles SCSS → CSS (with compression)
    - Runs ESLint checks
    - Processes CSS with autoprefixer
    - Minifies with PostCSS

2. **Runs Linters**

    - ESLint (JavaScript)
    - Stylelint (CSS/SCSS)
    - PHP CodeSniffer (PHP files)
    - PHPStan (static analysis)

3. **Creates Release Package** (using archiver)

    - Creates `dist/aranda-de-duero/` directory
    - Copies only necessary WordPress files
    - Excludes development files
    - Creates ZIP archive: `dist/aranda-de-duero-2.0.0.zip`

4. **Cleans Up**
    - Removes temporary dist directory
    - Keeps only the final ZIP file

## File Size Comparison

### Source Working Directory

-   With node_modules: ~750 MB
-   With vendor: ~25 MB
-   Development files: ~5 MB
-   **Total**: ~780 MB

### Release ZIP Package

-   All necessary files: ~2-3 MB
-   **Reduction**: 99.6% smaller! 🚀

## Versioning & Release Naming

The ZIP filename follows semantic versioning:

```
aranda-de-duero-{MAJOR}.{MINOR}.{PATCH}.zip
```

Examples:

-   `aranda-de-duero-2.0.0.zip` - Major version update
-   `aranda-de-duero-2.1.0.zip` - Minor feature update
-   `aranda-de-duero-2.0.1.zip` - Bug fix

Update the version in `package.json` before building:

```json
{
    "version": "2.1.0"
}
```

## Deployment Best Practices

### 1. Test Before Uploading

```bash
npm run build && npm start
# Thoroughly test all features at http://localhost:3000
```

### 2. Backup Existing Theme

If updating an existing theme, always keep a backup of the previous version.

### 3. Check WordPress Requirements

-   Minimum PHP: 7.4+ (see `composer.json`)
-   Minimum WordPress: 5.0 (see `phpcs.xml.dist`)

### 4. Verify After Upload

After uploading:

1. ✅ Theme appears in **Appearance → Themes**
2. ✅ No PHP errors in WordPress debug log
3. ✅ All CSS/JS files load correctly
4. ✅ Navigation and menus work
5. ✅ Pages and posts display correctly
6. ✅ Responsive design works on mobile

### 5. Enable Debug Logs (Development)

Add to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs at `wp-content/debug.log`

## Rollback Procedure

If something goes wrong after upload:

1. In WordPress Admin: Go to **Appearance → Themes**
2. Activate a different theme (WordPress provides a default)
3. Investigate the issue in the theme files
4. Upload the previous version again

Or via WP-CLI:

```bash
wp theme activate twentytwentythree
# Switch to previous version
wp theme activate aranda-de-duero
```

## CI/CD Integration

### Automated Releases with GitHub Actions

Create `.github/workflows/release.yml`:

```yaml
name: Release Theme

on:
    push:
        tags:
            - 'v*'

jobs:
    release:
        runs-on: ubuntu-latest
        steps:
            - uses: actions/checkout@v3
            - uses: actions/setup-node@v3
              with:
                  node-version: '18'

            - name: Install dependencies
              run: npm ci

            - name: Build release
              run: npm run release

            - name: Create Release
              uses: softprops/action-gh-release@v1
              with:
                  files: dist/*.zip
```

Usage:

```bash
git tag v2.0.0
git push origin v2.0.0
# GitHub Actions automatically builds and creates release
```

## Troubleshooting

### Release Build Fails

```bash
# Ensure all dependencies are installed
npm install
composer install

# Run linters to find issues
npm run lint
npm run lint:php

# Try build again
npm run release
```

### ZIP File Won't Upload

-   File size too large? Check your server limits
-   Extension issues? Ensure `.zip` extension is allowed
-   Permissions? Verify directory permissions on server

### Theme Not Appearing After Upload

```bash
# Verify theme directory permissions
chmod 755 /wp-content/themes/aranda-de-duero

# Verify all files copied correctly
ls -la /wp-content/themes/aranda-de-duero/

# Check theme header in style.css
head -10 /wp-content/themes/aranda-de-duero/style.css
```

### Missing CSS/JS After Upload

-   Check browser console for 404 errors
-   Verify CSS/JS file paths are correct
-   Ensure file permissions: `chmod 644 css/*.css js/*.js`

## Support

For questions about deployment:

1. Check this guide thoroughly
2. Review error logs: `wp-content/debug.log`
3. Check WordPress system requirements
4. Review the DEVELOPMENT.md guide

---

## Quick Reference Commands

```bash
# Development workflow
npm start                 # Watch mode with live reload

# Building
npm run build            # Build for production
npm run release          # Create distribution ZIP

# Linting
npm run lint             # Check all code quality
npm run lint:php         # Check PHP files only

# Cleanup
npm run clean            # Remove build artifacts
```

**Ready to deploy?** Run `npm run release` and upload the ZIP file! 🚀
