import React, { Suspense } from 'react';
import { createRoot } from 'react-dom/client';
import '@css/app.css';

// Code-split all pages under ./pages
const pages = import.meta.glob('./pages/**/*.jsx');

// Read mount info from host
function getMount() {
  const el = document.getElementById('app');
  const rawName  = el?.getAttribute('data-component') || 'home';
  const rawProps = el?.getAttribute('data-props') || '{}';
  let props = {};
  try { props = JSON.parse(rawProps); } catch {}
  return { el, rawName, props };
}

// Normalize names so you can pass 'home', 'home.index', 'users/show', 'Admin/Users.index'
function normalize(name) {
  let n = String(name).trim();
  n = n.replace(/\./g, '/');       // dots → slashes
  n = n.replace(/^\/+|\/+$/g, ''); // trim
  return n;                        // keep case as-is; match your filenames
}

(async () => {
  const { el, rawName, props } = getMount();
  if (!el) return;

  const name = normalize(rawName);

  // Try "<name>.jsx" then "<name>/index.jsx"
  const candidates = [
    `./pages/${name}.jsx`,
    `./pages/${name}/index.jsx`,
  ];

  let loader = null;
  for (const path of candidates) {
    if (pages[path]) { loader = pages[path]; break; }
  }

  if (!loader) {
    console.warn(`[vite] component "${name}" not found; falling back to NotFound`);
    // loader = () => import('./pages/NotFound.jsx'); // optional fallback
  }

  const Mod = (await loader()).default;
  createRoot(el).render(
    <Suspense fallback={<div />}>
      <Mod {...props} />
    </Suspense>
  );
})();
