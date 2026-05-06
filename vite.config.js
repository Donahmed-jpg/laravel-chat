import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import path from 'path';


// ─────────────────────────────────────────────────────────
// Module registry — will add new modules here as we create them
// ─────────────────────────────────────────────────────────

const modules = [
    'Auth',
    'Messaging',
    'Presence'
];

// Build input paths: each module's app.jsx entry + CSS
const moduleInputs = modules.flatMap(module => {
    const base = `modules/${module}/resources/js`;
    return [`${base}/index.js`];
}).filter(Boolean);

// Build aliases: @Auth → modules/Auth/resources/js etc.
const moduleAliases = modules.reduce((aliases, module) => {
    aliases[`@${module}`] = path.resolve(
        __dirname,
        `modules/${module}/resources/js`
    );
    return aliases;
}, {});

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: [
                'routes/**',
                'modules/**/resources/js/**',
                'resources/views/**',
            ],

        }),
        react(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    resolve: {
        alias: {
            // Global alias
            '@': path.resolve(__dirname, 'resources/js'),

            // Module Aliases - @Auth/Pages/Login -> modules/Auth/resources/js/Pages/Login
            ...moduleAliases,
        }
    }
});
