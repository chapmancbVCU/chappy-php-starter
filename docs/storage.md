---
layout: default
title: Session and Local storage
---

# Session and Local storage

## Table of contents

1. [Overview](#overview)
2. [Configuration](#configuration)
3. [The storage Utility](#storage-utility)
  - A. [Importing](#importing)
  - B. [Reading and Writing Values](#reading-and-writing)
  - C. [Expiring Values with TTL](#ttl)
  - D. [Removing and Clearing Values](#removing-and-clearing)
  - E. [Fallback Behavior](#fallback-behavior)
4. [React Hooks](#react-hooks)
  - A. [useLocalStorage](#use-local-storage)
  - B. [useSessionStorage](#use-session-storage)
  - C. [useStorage](#use-storage)
  - D. [Keeping Components in Sync](#keeping-in-sync)
5. [Choosing Between Local and Session Storage](#choosing)
6. [Security Considerations](#security)
7. [Testing with Vitest](#testing)
8. [API Reference](#api-reference)

<br>

<a id="overview"></a>
## 1. Overview [Table of Contents](#table-of-contents)

The `storage` utility gives your React views a safe way to save data in the browser using `localStorage` and `sessionStorage`. It adds several features that the browser APIs do not provide on their own:

- **Namespaced keys** - Every key is prefixed so your app's data does not collide with other apps or libraries on the same origin.
- **Automatic JSON serialization** - Store objects, arrays, numbers, and booleans directly.
- **Optional expiry** - Values can be given a time to live (TTL) in seconds, the same unit used by `defaultTtl` in the PHP `Api` class.
- **Graceful fallback** - If the browser blocks storage (private browsing, disabled site data, or a full quota), the utility falls back to in-memory storage instead of throwing errors.
- **React hooks** - `useLocalStorage` and `useSessionStorage` work like `useState`, but the value is saved in the browser and kept in sync across components and tabs.

Common uses include:

- Remembering user preferences such as units, theme, or page size
- Saving catalog filters and sort order while a user browses
- Autosaving drafts of long forms
- Tracking recently viewed items
- Keeping a guest's shopping cart before they log in

<br>

<a id="configuration"></a>
## 2. Configuration [Table of Contents](#table-of-contents)

By default all keys are prefixed with `chappy:`. You can change this by adding the following to your `.env` file:

```bash
VITE_STORAGE_PREFIX=my_app
```

Setting a unique prefix is recommended when you run more than one Chappy.php project on the same address during development (for example `localhost:8000`). Browsers share storage between all pages on the same scheme, host, and port, so two apps using the same prefix could read or clear each other's data.

**NOTE:** Only variables starting with `VITE_` are exposed to the browser. Never put secrets in a `VITE_` variable. Because Vite reads this value at build time, restart `npm run dev` or rebuild your assets after changing it.

<br>

<a id="storage-utility"></a>
## 3. The storage Utility [Table of Contents](#table-of-contents)

<br>

<a id="importing"></a>
### A. Importing

The utility exports two stores, `local` and `session`, which share the same methods.

```jsx
import { local, session } from '@chappy/utils/storage';
```

You can also use the default export:

```jsx
import storage from '@chappy/utils/storage';

storage.local.get('weather.units');
storage.session.get('catalog.filters');
```

<br>

<a id="reading-and-writing"></a>
### B. Reading and Writing Values

Use `set()` to save a value and `get()` to read it back.

```jsx
local.set('weather.units', 'metric');

const units = local.get('weather.units', 'imperial');
```

The second argument to `get()` is a fallback that is returned when the key does not exist, has expired, or contains invalid data. If no fallback is provided, `null` is returned.

Any JSON-serializable value can be stored:

```jsxc
session.set('catalog.filters', { genre: 'rpg', platform: 'switch', sort: 'price' });

const filters = session.get('catalog.filters', {});
```

`set()` returns `true` when the value was saved and `false` when it could not be saved, such as when the storage quota is full. It never throws, so your component will not crash if a write fails.

```jsx
if (!local.set('recent.games', ids)) {
    // Value was not saved. Continue without it.
}
```

<br>

<a id="ttl"></a>
### C. Expiring Values with TTL

Pass a `ttl` option in seconds to make a value expire. Expired values are treated as missing and `get()` returns the fallback.

```jsx
// Expires in one week
local.set('recent.games', ids, { ttl: 60 * 60 * 24 * 7 });

// Expires in 15 minutes
session.set('catalog.filters', filters, { ttl: 900 });
```

When no `ttl` is provided the value never expires.

<br>

<a id="removing-and-clearing"></a>
### D. Removing and Clearing Values

Remove a single key:

```jsx
local.remove('weather.units');
```

List all keys saved by your app:

```jsx
const keys = local.keys(); // ['weather.units', 'recent.games']
```

Remove every key saved by your app:

```jsx
local.clear();
session.clear();
```

`clear()` only removes keys that use your prefix. Data saved by other apps or libraries on the same origin is left alone.

<br>

<a id="fallback-behavior"></a>
### E. Fallback Behavior

Some browsers block storage, for example in private browsing mode or when the user has disabled site data. When this happens the store switches to in-memory storage. Your code keeps working, but data is lost when the page reloads.

You can check which mode a store is using with the `persistent` property:

```jsx
if (!local.persistent) {
    console.info('Preferences will not be saved after this page reloads.');
}
```

<br>

<a id="react-hooks"></a>
## 4. React Hooks [Table of Contents](#table-of-contents)

The hooks are imported from the same module:

```jsx
import { useLocalStorage, useSessionStorage } from '@chappy/utils/storage';
```

Each hook returns an array with three items:

1. The current value
2. A setter function
3. A function that removes the key

<br>

<a id="use-local-storage"></a>
### A. useLocalStorage

Saves a value in `localStorage`. The value persists after the browser is closed and is shared across all tabs.

```jsx
function UnitToggle() {
    const [units, setUnits] = useLocalStorage('weather.units', 'imperial');

    return (
        <select value={units} onChange={e => setUnits(e.target.value)} className="form-select">
            <option value="imperial">Fahrenheit</option>
            <option value="metric">Celsius</option>
        </select>
    );
}
```

The setter also accepts an updater function, just like `useState`:

```jsx
const [recent, setRecent] = useLocalStorage('recent.games', [], { ttl: 60 * 60 * 24 * 7 });

function addRecent(id) {
    setRecent(prev => [id, ...prev.filter(x => x !== id)].slice(0, 10));
}
```

The third argument is an options object. When `ttl` is set, it applies to every write made through the setter.

<br>

<a id="use-session-storage"></a>
### B. useSessionStorage

Saves a value in `sessionStorage`. The value persists through page reloads but only within the current tab. It is removed when the tab is closed.

```jsx
function ProductDescriptionField() {
    const [draft, setDraft, clearDraft] = useSessionStorage('product.draft', '');

    const handleSubmit = async () => {
        // Submit form...
        clearDraft();
    };

    return (
        <textarea
            className="form-control"
            value={draft}
            onChange={e => setDraft(e.target.value)}
        />
    );
}
```

<br>

<a id="use-storage"></a>
### C. useStorage

`useLocalStorage` and `useSessionStorage` are shortcuts for the more general `useStorage` hook. Use it when the storage type is decided at runtime.

```jsx
import { useStorage } from '@chappy/utils/storage';

const [value, setValue, remove] = useStorage(rememberMe ? 'local' : 'session', 'login.email', '');
```

<br>

<a id="keeping-in-sync"></a>
### D. Keeping Components in Sync

All components that use the same key share the same value. When one component updates it, every other component using that key re-renders automatically.

- **Same tab** - Works for both `local` and `session`.
- **Other tabs** - Works for `local` only. Changing a value in one tab updates any open tab using that key.

This also works when values are changed with the utility functions directly. For example, calling `local.set('weather.units', 'metric')` outside a component updates every component using `useLocalStorage('weather.units', ...)`.

**TIP:** The initial value is only read on the first render, so it is safe to pass an object or array literal:

```jsx
const [filters, setFilters] = useSessionStorage('catalog.filters', { genre: 'all', sort: 'name' });
```

<br>

<a id="choosing"></a>
## 5. Choosing Between Local and Session Storage [Table of Contents](#table-of-contents)

| | `local` | `session` |
|---|---|---|
| Survives page reload | Yes | Yes |
| Survives closing the tab | Yes | No |
| Shared across tabs | Yes | No |
| Good for | Preferences, recently viewed items, guest carts | Filters, sort order, form drafts, multi-step wizards |

As a rule of thumb, use `local` for things the user would expect to be remembered next time they visit, and `session` for things that only matter while they are working in the current tab.

<br>

<a id="security"></a>
## 6. Security Considerations [Table of Contents](#table-of-contents)

Data in web storage can be read by any JavaScript running on the page. If your app ever has a cross-site scripting (XSS) vulnerability, everything in storage is exposed. Follow these rules:

- **Do not** store authentication tokens, session IDs, or CSRF tokens. Chappy.php handles authentication with server-side sessions and cookies, and that should remain the source of truth.
- **Do not** store passwords or personally identifiable information such as addresses, phone numbers, or payment details.
- **Do** treat stored values as untrusted input. A user can edit storage in their browser's developer tools, so validate anything that is sent back to the server.
- **Do** clear user-specific data when a user logs out, especially on shared computers:

```jsx
import { local, session } from '@chappy/utils/storage';

local.clear();
session.clear();
```

<br>

<a id="testing"></a>
## 7. Testing with Vitest [Table of Contents](#table-of-contents)

The jsdom environment used by Vitest provides both `localStorage` and `sessionStorage`, so no extra setup is required. Clear storage before each test to keep tests independent.

```jsx
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { local } from '@chappy/utils/storage';

describe('storage', () => {
    beforeEach(() => {
        localStorage.clear();
        vi.useRealTimers();
    });

    it('returns stored values', () => {
        local.set('units', 'metric');
        expect(local.get('units')).toBe('metric');
    });

    it('returns the fallback for missing keys', () => {
        expect(local.get('missing', 'imperial')).toBe('imperial');
    });

    it('expires values after the TTL', () => {
        vi.useFakeTimers();
        local.set('temp', 'value', { ttl: 60 });

        vi.advanceTimersByTime(61 * 1000);

        expect(local.get('temp')).toBeNull();
    });
});
```

To test hooks, render a component with `@testing-library/react` and wrap updates in `act()`:

```jsx
import { act, renderHook } from '@testing-library/react';
import { useLocalStorage } from '@chappy/utils/storage';

it('updates the stored value', () => {
    const { result } = renderHook(() => useLocalStorage('units', 'imperial'));

    act(() => result.current[1]('metric'));

    expect(result.current[0]).toBe('metric');
    expect(localStorage.getItem('chappy:units')).toContain('metric');
});
```

To simulate a change from another tab, dispatch a `StorageEvent`:

```jsx
act(() => {
    localStorage.setItem('chappy:units', JSON.stringify({ value: 'metric' }));
    window.dispatchEvent(new StorageEvent('storage', { key: 'chappy:units' }));
});
```

<br>

<a id="api-reference"></a>
## 8. API Reference [Table of Contents](#table-of-contents)

### Store Methods

Available on both `local` and `session`.

| Method | Description |
|---|---|
| `get(key, fallback = null)` | Returns the stored value, or `fallback` if missing, malformed, or expired. |
| `set(key, value, { ttl })` | Saves a JSON-serializable value. `ttl` is optional and in seconds. Returns `true` on success and `false` on failure. |
| `remove(key)` | Deletes a single key. |
| `keys()` | Returns an array of all keys saved by your app in this store. |
| `clear()` | Deletes all keys saved by your app in this store. |
| `raw(key)` | Returns the raw stored string without parsing. Mainly used internally by the hooks. |
| `fullKey(key)` | Returns the key with the prefix applied. |

### Store Properties

| Property | Description |
|---|---|
| `type` | Either `'local'` or `'session'`. |
| `persistent` | `true` when using real browser storage, `false` when using the in-memory fallback. |

### Hooks

| Hook | Description |
|---|---|
| `useLocalStorage(key, initialValue, { ttl })` | Binds a component to a `localStorage` key. Returns `[value, setValue, remove]`. |
| `useSessionStorage(key, initialValue, { ttl })` | Binds a component to a `sessionStorage` key. Returns `[value, setValue, remove]`. |
| `useStorage(type, key, initialValue, { ttl })` | General form of the hooks above. `type` is `'local'` or `'session'`. |

### Constants

| Constant | Description |
|---|---|
| `CHANGE_EVENT` | Name of the window event (`chappy:storage`) dispatched when a value changes in the current tab. The event's `detail` contains `{ type, key }`, where `key` is `null` after `clear()`. |