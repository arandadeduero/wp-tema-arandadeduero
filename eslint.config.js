const js = require('@eslint/js');
const globals = require('globals');
const prettierPlugin = require('eslint-plugin-prettier');
const prettierConfig = require('eslint-config-prettier');

module.exports = [
    {
        ignores: ['js/*.min.js', 'js/bootstrap.bundle.min.js', 'node_modules/**', 'vendor/**', 'dist/**'],
    },
    js.configs.recommended,
    {
        files: ['js/**/*.js'],
        languageOptions: {
            ecmaVersion: 2021,
            sourceType: 'module',
            globals: {
                ...globals.browser,
                ...globals.node,
                wp: 'readonly',
                wpData: 'readonly',
                ArandaTheme: 'readonly',
                NavigationMenu: 'readonly',
            },
        },
        plugins: {
            prettier: prettierPlugin,
        },
        rules: {
            ...prettierConfig.rules,
            'no-console': ['warn', { allow: ['warn', 'error'] }],
            'no-unused-vars': ['warn', { args: 'after-used' }],
            'prefer-const': 'warn',
            'no-var': 'warn',
            eqeqeq: ['warn', 'always'],
            semi: ['warn', 'always'],
            quotes: ['warn', 'single', { avoidEscape: true }],
            indent: ['warn', 4, { SwitchCase: 1 }],
            'prettier/prettier': 'warn',
        },
    },
];
