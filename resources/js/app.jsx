/**
 * Application Entry Point
 *
 * This file does three things:
 *
 *  1. Discovers all Inertia page components across every module
 *     using Vite's import.meta.glob — this happens at BUILD TIME,
 *     not at runtime. Vite scans the file system and creates a static
 *     map of { filePath → () => import(component) }.
 *
 *  2. Teaches Inertia how to resolve a component name like 'Auth/Login'
 *     to the actual React component at
 *     modules/Auth/resources/js/Pages/Login.jsx
 *
 *  3. Boots the Inertia + React application inside the single
 *     <div id="app"> that lives in resources/views/app.blade.php
 */

import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import '../css/app.css'

// ─────────────────────────────────────────────────────────────────────────────
// PAGE DISCOVERY
//
// import.meta.glob is a Vite-specific API. It runs at BUILD TIME and returns
// a plain object shaped like:
//
//   {
//     '../../modules/Auth/resources/js/Pages/Login.jsx':
//         () => import('../../modules/Auth/resources/js/Pages/Login.jsx'),
//
//     '../../modules/Auth/resources/js/Pages/Register.jsx':
//         () => import('../../modules/Auth/resources/js/Pages/Register.jsx'),
//
//     '../../modules/Messaging/resources/js/Pages/Chat/Index.jsx':
//         () => import('../../modules/Messaging/resources/js/Pages/Chat/Index.jsx'),
//   }
//
// The values are LAZY — they are arrow functions that return a dynamic import
// Promise. The component is not actually loaded until resolve() calls it.
// This gives us automatic code splitting per page for free.
//
// PATH NOTE:
//   This file lives at:  laravel-chat/resources/js/app.jsx
//   One ../   resolves to:  laravel-chat/resources/
//   Two ../../ resolves to:  laravel-chat/          ← project root
//   So ../../modules/ correctly points to laravel-chat/modules/
// ─────────────────────────────────────────────────────────────────────────────
const pages = import.meta.glob([
    // Primary: scan every module's Pages directory recursively
    // Matches: modules/Auth/resources/js/Pages/Login.jsx
    // Matches: modules/Messaging/resources/js/Pages/Chat/Index.jsx
    '../../modules/*/resources/js/Pages/**/*.jsx',

    // Fallback: global pages not belonging to any module
    // e.g. resources/js/Pages/Dashboard.jsx
    // We don't have any right now but the resolver supports them
    './Pages/**/*.jsx',
])

// ─────────────────────────────────────────────────────────────────────────────
// PAGE NAME MATCHER
//
// Inertia receives a component name string from Laravel, e.g.:
//   Inertia::render('Auth/Login')        → name = 'Auth/Login'
//   Inertia::render('Messaging/Chat/Index') → name = 'Messaging/Chat/Index'
//
// This function answers: "does this file path correspond to that name?"
//
// It handles two cases:
//
//   Case 1 — Module page:
//     filePath = '../../modules/Auth/resources/js/Pages/Login.jsx'
//     We extract:  moduleName = 'Auth',  pagePath = 'Login'
//     We build:    'Auth/Login'
//     We compare:  'Auth/Login' === name  → true 
//
//   Case 2 — Global page (fallback):
//     filePath = './Pages/Dashboard.jsx'
//     We extract:  pagePath = 'Dashboard'
//     We compare:  'Dashboard' === name   → true 
//
// The regex uses no ^ anchor intentionally — it matches the relevant
// portion of the path regardless of how many ../ prefixes exist.
// ─────────────────────────────────────────────────────────────────────────────
function matchesPageName(filePath, name) {
    // Case 1: module page
    // Captures everything after /Pages/ and before .jsx
    // e.g. modules/Auth/resources/js/Pages/Chat/Index.jsx
    //   → moduleName = 'Auth', pagePath = 'Chat/Index'
    const moduleMatch = filePath.match(
        /modules\/(\w+)\/resources\/js\/Pages\/(.+)\.jsx$/
    )

    if (moduleMatch) {
        const [, moduleName, pagePath] = moduleMatch
        return `${moduleName}/${pagePath}` === name
    }

    // Case 2: global page (no module prefix)
    const globalMatch = filePath.match(/Pages\/(.+)\.jsx$/)
    if (globalMatch) {
        return globalMatch[1] === name
    }

    return false
}

// ─────────────────────────────────────────────────────────────────────────────
// PAGE RESOLVER
//
// Inertia calls this function every time it needs to render a page.
// It receives the component name string that Laravel passed to Inertia::render()
// and must return either:
//   - The component module itself  (if pre-loaded / eager)
//   - A Promise resolving to it    (if lazy — which is our case)
//
// Flow:
//   Laravel returns  { component: 'Auth/Login', props: { ... } }
//   Inertia calls    resolve('Auth/Login')
//   We search        pages map for a matching file path
//   We return        the lazy import function → () => import('...Login.jsx')
//   Inertia awaits   the Promise and renders the component with props
// ─────────────────────────────────────────────────────────────────────────────
function resolvePageComponent(name) {
    // Find the first entry in the pages map whose file path
    // corresponds to the requested component name
    const match = Object.entries(pages).find(
        ([filePath]) => matchesPageName(filePath, name)
    )

    // If nothing matched, we have a bug — either Laravel passed the wrong
    // component name or the file doesn't exist / isn't in the glob pattern.
    // Fail loudly so it's obvious during development.
    if (!match) {
        throw new Error(
            `Inertia page not found: "${name}"\n\n` +
            `Searched all module Pages/ directories.\n` +
            `Make sure the file exists and matches the pattern:\n` +
            `  modules/{Module}/resources/js/Pages/{name}.jsx\n\n` +
            `Currently discovered pages:\n` +
            Object.keys(pages).map(p => `  ${p}`).join('\n')
        )
    }

    // match is: [filePath, moduleOrFunction]
    // The value is always a function (lazy import) because import.meta.glob
    // returns lazy loaders by default — call it to get the Promise.
    const [, importFn] = match
    return importFn()
}

// ─────────────────────────────────────────────────────────────────────────────
// APPLICATION BOOT
//
// createInertiaApp wires everything together:
//
//   title    — wraps every page's <Head title="X"> as "X — Laravel Chat"
//              Pages set their title via: <Head title="Login" />
//
//   resolve  — our custom multi-module resolver defined above
//
//   setup    — standard React 18 root mount
//              el      = the <div id="app"> in app.blade.php
//              App     = Inertia's root component (manages page transitions)
//              props   = the initial page's component name + props from Laravel
//
//   progress — shows a slim loading bar at the top during page transitions
//              color matches our indigo design token
// ─────────────────────────────────────────────────────────────────────────────
createInertiaApp({
    title: (title) => `${title} — ${import.meta.env.VITE_APP_NAME ?? 'Chat'}`,

    resolve: resolvePageComponent,

    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },

    progress: {
        color: '#6366f1',
    },
})