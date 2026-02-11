#!/usr/bin/env node

/**
 * Build Release Script
 * 
 * Creates a clean WordPress theme package with only necessary files.
 * Compiles all assets and creates a distribution ZIP archive.
 * 
 * Usage: npm run release
 */

const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

// Theme information
const THEME_NAME = 'aranda-de-duero';
const THEME_VERSION = '2.0.0';
const DIST_DIR = path.join(__dirname, '..', 'dist');
const RELEASE_DIR = path.join(DIST_DIR, THEME_NAME);
const OUTPUT_ZIP = path.join(__dirname, '..', `${THEME_NAME}-${THEME_VERSION}.zip`);

// Files and directories to include in the release
const FILES_TO_INCLUDE = [
    '404.php',
    'archive.php',
    'comments.php',
    'footer.php',
    'functions.php',
    'header.php',
    'index.php',
    'page.php',
    'search.php',
    'searchform.php',
    'sidebar.php',
    'single.php',
    'style.css',
    'style-rtl.css',
];

// Pages and custom post types
const TEMPLATE_PAGES = [
    'page-agenda.php',
    'page-ayuntamiento.php',
    'page-bandos.php',
    'page-home.php',
    'page-legales.php',
    'page-multimedia.php',
    'page-news.php',
    'page-sinsidebars.php',
    'page-tramite.php',
    'page-villa.php',
    'page-webmap.php',
];

// Taxonomies
const TAXONOMY_TEMPLATES = [
    'taxonomy-concejalia-cultura-y-educacion.php',
    'taxonomy-concejalia-juventud.php',
    'taxonomy-concejalia-urbanismo-y-vivienda.php',
    'taxonomy-concejalia.php',
    'taxonomy-seccion.php',
    'taxonomy-tema-anuncios.php',
    'taxonomy-tema-ayudas-y-subvenciones.php',
    'taxonomy-tema-concursos.php',
    'taxonomy-tema-consumo-y-comercio.php',
    'taxonomy-tema-cultura-y-ocio.php',
    'taxonomy-tema-educacion.php',
    'taxonomy-tema-empleo.php',
    'taxonomy-tema-movilidad-y-transportes.php',
    'taxonomy-tema-oficina-desarrollo-local.php',
    'taxonomy-tema-oposiciones-y-empleo.php',
    'taxonomy-tema-participacion-ciudadana.php',
    'taxonomy-tema-salud.php',
    'taxonomy-tema-seguridad-ciudadana.php',
    'taxonomy-tema-servicios-sociales.php',
    'taxonomy-tema-tributos.php',
    'taxonomy-tema-urbanismo-e-infraestructuras.php',
    'taxonomy-tema.php',
];

// Custom post types
const CUSTOM_POST_TEMPLATES = [
    'single-mc-events.php',
    'single-tramite.php',
];

const DIRECTORIES_TO_INCLUDE = [
    'css',
    'inc',
    'images',
    'js',
    'languages',
    'template-parts',
];

const FILES_METADATA = [
    'LICENSE',
    'README.md',
];

// Clean and create dist directory
function ensureDistDirectory() {
    if (fs.existsSync(DIST_DIR)) {
        fs.rmSync(DIST_DIR, { recursive: true, force: true });
    }
    fs.mkdirSync(DIST_DIR, { recursive: true });
    fs.mkdirSync(RELEASE_DIR, { recursive: true });
}

// Copy file
function copyFile(src, dest) {
    const srcPath = path.join(__dirname, '..', src);
    const destPath = path.join(RELEASE_DIR, dest || src);

    // Ensure destination directory exists
    const destDir = path.dirname(destPath);
    if (!fs.existsSync(destDir)) {
        fs.mkdirSync(destDir, { recursive: true });
    }

    if (fs.existsSync(srcPath)) {
        fs.copyFileSync(srcPath, destPath);
        return true;
    }
    return false;
}

// Recursively copy directory, excluding specific files/patterns
function copyDir(src, dest, excludePatterns = []) {
    const srcPath = path.join(__dirname, '..', src);
    const destPath = path.join(RELEASE_DIR, dest || src);

    if (!fs.existsSync(srcPath)) {
        return;
    }

    fs.mkdirSync(destPath, { recursive: true });

    const entries = fs.readdirSync(srcPath);

    entries.forEach((entry) => {
        const srcEntry = path.join(srcPath, entry);
        const destEntry = path.join(destPath, entry);
        const stat = fs.statSync(srcEntry);

        // Check if should exclude
        const shouldExclude = excludePatterns.some((pattern) => {
            if (typeof pattern === 'string') {
                return entry === pattern;
            }
            return pattern.test(entry);
        });

        if (shouldExclude) {
            return;
        }

        if (stat.isDirectory()) {
            copyDir(path.join(src, entry), path.join(dest || src, entry), excludePatterns);
        } else {
            fs.copyFileSync(srcEntry, destEntry);
        }
    });
}

// Create archive
function createArchive() {
    return new Promise((resolve, reject) => {
        const output = fs.createWriteStream(OUTPUT_ZIP);
        const archive = archiver('zip', {
            zlib: { level: 9 }, // Maximum compression
        });

        output.on('close', () => {
            const stats = fs.statSync(OUTPUT_ZIP);
            const sizeKb = (stats.size / 1024).toFixed(2);

            // Cleanup dist directory
            fs.rmSync(DIST_DIR, { recursive: true, force: true });

            console.log(`\n✅ Release package created successfully!`);
            console.log(`📦 File: ${OUTPUT_ZIP}`);
            console.log(`📊 Size: ${sizeKb} KB\n`);

            resolve();
        });

        output.on('error', (err) => {
            reject(err);
        });

        archive.on('error', (err) => {
            reject(err);
        });

        archive.pipe(output);

        // Add files
        console.log('📝 Adding files to archive...');

        FILES_TO_INCLUDE.forEach((file) => {
            const srcPath = path.join(__dirname, '..', file);
            if (fs.existsSync(srcPath)) {
                archive.file(srcPath, { name: path.join(THEME_NAME, file) });
            }
        });

        TEMPLATE_PAGES.forEach((file) => {
            const srcPath = path.join(__dirname, '..', file);
            if (fs.existsSync(srcPath)) {
                archive.file(srcPath, { name: path.join(THEME_NAME, file) });
            }
        });

        TAXONOMY_TEMPLATES.forEach((file) => {
            const srcPath = path.join(__dirname, '..', file);
            if (fs.existsSync(srcPath)) {
                archive.file(srcPath, { name: path.join(THEME_NAME, file) });
            }
        });

        CUSTOM_POST_TEMPLATES.forEach((file) => {
            const srcPath = path.join(__dirname, '..', file);
            if (fs.existsSync(srcPath)) {
                archive.file(srcPath, { name: path.join(THEME_NAME, file) });
            }
        });

        // Add directories
        DIRECTORIES_TO_INCLUDE.forEach((dir) => {
            const srcPath = path.join(__dirname, '..', dir);
            if (fs.existsSync(srcPath)) {
                archive.directory(srcPath, path.join(THEME_NAME, dir));
            }
        });

        // Add metadata
        FILES_METADATA.forEach((file) => {
            const srcPath = path.join(__dirname, '..', file);
            if (fs.existsSync(srcPath)) {
                archive.file(srcPath, { name: path.join(THEME_NAME, file) });
            }
        });

        archive.finalize();
    });
}

// Main execution
async function build() {
    console.log(`\n🚀 Building Release Package`);
    console.log(`📦 Theme: ${THEME_NAME} v${THEME_VERSION}\n`);

    try {
        console.log('🗂️  Preparing dist directory...');
        ensureDistDirectory();

        console.log('📋 Creating archive...');
        await createArchive();

        console.log(`✨ Ready to upload to your WordPress server!\n`);
    } catch (error) {
        console.error('❌ Build failed:', error);
        process.exit(1);
    }
}

build();
