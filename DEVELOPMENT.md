# Development Guide - Aranda de Duero Theme

This guide explains how to use the modern development tools and build system for the Aranda de Duero WordPress theme.

## Prerequisites

-   **Node.js**: 18.0.0 or higher
-   **npm**: 9.0.0 or higher
-   **PHP**: 7.4 or higher
-   **Composer**: Latest version

## Project Setup

### 1. Install Node Dependencies

```bash
npm install
```

This installs all development tools:
-   **eslint**: JavaScript linting
-   **prettier**: Code formatter
-   **stylelint**: CSS linting
-   **postcss**: CSS processing
-   **autoprefixer**: Browser vendor prefixes
-   **cssnano**: CSS minification
-   **browser-sync**: Local development server with live reload
-   **@wordpress/scripts**: WordPress development utilities

### 2. Install PHP Dependencies

```bash
composer install
```

This installs:

-   **WordPress Coding Standards (WPCS)**: PHP code quality rules
-   **PHPStan**: PHP static analyzer
-   **PHP Parallel Lint**: Fast PHP syntax checker

## Available npm Scripts

### Development Workflow

**Start development server with watch mode:**

```bash
npm start
```

- Watches all source files (`.js`, `.php`, `.css`)
- Runs ESLint and Stylelint on changes
- Launches browser-sync for live reload

**Watch files without starting browser-sync:**

```bash
npm run watch
```

-   Monitors CSS and JavaScript files
-   Compiles and lints automatically
-   No browser-sync server

### Production Build

**Create minified production-ready assets:**

```bash
npm run build
```

-   Minifies JavaScript
-   Optimizes CSS with autoprefixer + cssnano
-   Generates RTL stylesheet

### Code Quality

**Run all linters:**

```bash
npm run lint
```

**Lint JavaScript only:**

```bash
npm run lint:js
```

-   Uses ESLint with WordPress plugin
-   Checks for jQuery references (should be 0)

**Lint CSS only:**

```bash
npm run lint:style
```

-   Uses Stylelint with CSS support
-   Enforces consistent formatting

**Lint PHP files:**

```bash
npm run lint:php
```

-   Uses PHP CodeSniffer with WordPress Coding Standards
-   Enforces WPCS naming conventions (uses `aranda_de_duero_` prefix)

**Run PHPStan static analysis:**

```bash
npm run phpstan
```

-   Performs static PHP code analysis
-   Catches potential type/logic errors

### Code Formatting

**Auto-fix linting issues:**

```bash
npm run format
```

-   Fixes ESLint issues (where possible)
-   Fixes Stylelint issues
-   Reformats code with Prettier

**Format with Prettier only:**

```bash
npm run format:prettier
```

-   Applies consistent code style:
    -   4-space indentation
    -   100 character line width
    -   Single quotes for strings
    -   Trailing commas in multi-line arrays/objects

### Build Utilities

**Extract theme translation strings:**

```bash
npm run compile:rtl
```

-   Generates RTL version of stylesheet
-   Useful for testing RTL language support

**Clean build artifacts:**

```bash
npm run clean
```

-   Removes compiled CSS and minified assets

## File Organization

```
├── js/                          # JavaScript source files
│   ├── aranda-de-duero.js      # Main theme functionality
│   ├── customizer.js           # Theme Customizer integration
│   └── navigation.js           # Navigation menu handler
│
├── css/                         # CSS source files
│   └── aranda-de-duero.css     # Main stylesheet (1,693 lines)
│
├── template-parts/             # Reusable template components
├── inc/                        # PHP includes
│   ├── custom-header.php
│   ├── customizer.php
│   ├── jetpack.php
│   ├── template-functions.php
│   └── template-tags.php
│
├── package.json                # Node.js dependencies & scripts
├── composer.json               # PHP dependencies & scripts
├── phpcs.xml.dist              # PHP CodeSniffer configuration
├── .eslintrc.json              # ESLint JavaScript linting rules
├── .prettierrc                 # Prettier code formatting config
├── .stylelintrc.json           # Stylelint CSS linting rules
├── postcss.config.js           # PostCSS processing (autoprefixer, cssnano)
├── .browserslistrc             # Target browsers for autoprefixer
├── .npmrc                      # npm configuration
└── .prettierignore             # Prettier exclusion patterns
```

## Configuration Files Explained

### .eslintrc.json

Enforces JavaScript code quality:

-   ES2021 JavaScript syntax
-   WordPress-specific globals: `wp`, `wpData`, `ArandaTheme`, `NavigationMenu`
-   Prefers `const`/`let` over `var`
-   Requires semicolons
-   Enforces strict equality (`===`)
-   4-space indentation

### .prettierrc

Ensures consistent code formatting:

-   4-space indentation (tabs)
-   100 character line width
-   Single quotes for strings
-   Trailing commas in ES5 objects/arrays
-   Proper arrow parentheses

### .stylelintrc.json

Validates CSS quality:

-   CSS syntax support
-   Consistent selector naming
-   Proper selector formatting
-   Media query organization
-   Integrates with Prettier for consistent formatting

### postcss.config.js

Processes compiled CSS:

-   **Autoprefixer**: Adds vendor prefixes for browser compatibility
-   **CSSnano**: Minifies CSS with intelligent compression
-   Supports all browsers with >1% market share (last 2 versions)

### composer.json

PHP development tools:

-   WordPress Coding Standards (WPCS 3.0.0)
-   PHPStan for static analysis
-   PHP Parallel Lint for syntax checking

## Modern JavaScript Features

All JavaScript files use modern ES6+ syntax:

-   Arrow functions: `() => {}`
-   `const`/`let` declarations (no `var`)
-   Template literals: `` `hello ${name}` ``
-   Destructuring: `const { name, age } = person`
-   IIFE module pattern for scope isolation
-   DOM utility helpers for vanilla JavaScript

### No jQuery!

The theme is 100% jQuery-free. All DOM manipulation uses vanilla JavaScript:

```js
// Before (jQuery)
$('.element').addClass('active');

// After (Vanilla)
document.querySelector('.element').classList.add('active');
```

## CSS Features

The stylesheet uses modern CSS features:

-   **CSS Custom Properties (Variables)**: 50+ theme variables
-   **CSS Grid & Flexbox**: Modern layout techniques
-   **Reduced Motion**: Respects `prefers-reduced-motion` setting
-   **Dark Mode**: `prefers-color-scheme` media query support
-   **Print Styles**: Optimized for printing

### CSS Variables

```css
:root {
    --color-primary: #0073aa;
    --color-text: #333;
    --spacing-base: 1rem;
    --transition-default: 0.3s ease;
}
```

## Development Workflow Examples

### Example 1: Adding a New Feature

```bash
# Start watch mode
npm start

# Edit your files (js, css, php)
# Files are automatically compiled and linted

# When ready, run build for production
npm run build

# Lint everything before committing
npm run lint
npm run lint:php
```

### Example 2: Fixing Linting Issues

```bash
# See all linting problems
npm run lint
npm run lint:php

# Auto-fix what's fixable
npm run format

# Manually fix remaining issues
# (errors that can't auto-fix will be reported)
```

### Example 3: Testing on Different Browsers

```bash
# Browser-sync serves the site locally and syncs across devices/browsers
npm start

# Visits automatically open at http://localhost:3000/
# Manual testing on other devices: http://[your-ip]:3000/
```

## Common Issues & Solutions

### "npm: command not found"

-   Install Node.js from https://nodejs.org/
-   Ensure version 18+ is installed: `node --version`

### CSS compilation errors

-   Check for invalid CSS syntax
-   Run `npm run lint:style` to check CSS
-   Look for unexpanded variables or missing semicolons

### ESLint errors about WordPress globals

-   Add global declarations in `.eslintrc.json` under `globals`
-   Use `/* global MyNamespace */` at file start
-   Ensure prefix matches `aranda_de_duero` convention

### Browser-sync not working

-   Ensure port 3000 is available
-   Check firewall settings
-   Verify `browser-sync` is installed: `npm list browser-sync`

### PHP linter fails

-   Check prefix: all globals must start with `aranda_de_duero_`
-   Run `npm run lint:php` to see specific issues
-   Verify PHP 7.4+ syntax (no older PHP 5.x features)

## CI/CD Integration

These npm scripts are designed for automation:

### GitHub Actions Example

```yaml
- name: Install dependencies
  run: npm ci

- name: Run linters
  run: npm run lint

- name: Check formatting
  run: npx prettier --check . --ignore-path .prettierignore

- name: Build production assets
  run: npm run build
```

## Performance Notes

-   **JavaScript**: Reduced from 87KB (jQuery) to modular vanilla JS
-   **CSS**: Optimized with 50+ variables for easy theming
-   **Build Time**: ~2 seconds for development, ~5 seconds for production
-   **Live Reload**: Instant with browser-sync during `npm start`

## Further Reading

-   [ESLint Documentation](https://eslint.org/)
-   [Prettier Documentation](https://prettier.io/)
-   [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
-   [PostCSS Documentation](https://postcss.org/)
-   [Stylelint Documentation](https://stylelint.io/)

## Support

For issues or questions:

1. Check the relevant configuration file
2. Review error messages from `npm run lint`
3. Consult the WordPress Coding Standards guide
4. Check the MODERNIZATION.md for architecture details
