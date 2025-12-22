<h1 style="font-size: 50px; text-align: center;">Using APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [api.js](#api-js)
    * A. [Request Helpers](#request-helpers)
    * B. [Options (opts) Shared by All Helpers](#options)
    * C. [Error Helper](#error-helper)
    * D. [Core Client](#core-client)
    * E. [React Hook](#react-hook)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide provides a detailed overview for the everything needed to user APIs with this framework.

The two main components:
- api.js - A utility available for use with JavaScript to perform API related tasks.
- JsonResponse.php - A trait available for use by your controller classes to support API related tasks.

<br>

## 2. api.js <a id="api-js"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This file contains all of the API utilities needed to perform operations with JavaScript.  This file provides two things:

1. **A small HTTP client** built on `fetch()` (`apiRequest`) with convenience wrappers (`apiGet`, `apiPost`, etc.).
2. **A React hook** (`useAsync`) that runs async work and manages `{ data, loading, error }`, with built-in cancellation to prevent stale updates.

These utilities are intended for **same-origin** API calls (your Chappy.php app and its API endpoints), and they work with CSRF protection and cookie-base authentication.

<br>

### A. Request Helpers <a id="request-helpers"></a>
#### 1. `apiGet(path, opts)`
Runs a **GET** request.

**Typical Use Cases**
- Fetching lists, detail views, search results
- Requests that should not include a body

**Example**
```js
const weather = await apiGet('/api/weather', {
  query: { q: 'Austin', units: 'imperial' }
});
```

<br>

#### 2. `apiPost(path, body, opts)`
Runs a **POST** request and sends `body` (JSON by default).

**Typical Use Cases**
- Creating records
- Submitting forms

**Example**
```js
await apiPost('/api/favorites', { placeId: 123 }, {
  headers: { 'X-CSRF-Token': getCsrf() }
});
```

<br>

#### 3. `apiPut(path, body, opts)`
Runs a **PUT** request and sends `body` (JSON by default).

**Typical Use Cases**
- Full record updates (replace semantics)

<br>

#### 4. `apiPatch(path, body, opts)`
Runs a **Patch** request and sends `body` (JSON by default).

**Typical Use Cases**
- Partial updates (change one field like `is_home`, notes, etc.)

**Example**
```js
const payload = {
    csrf_token: Forms.CSRFToken(e)
}
const json = await apiPatch(`/favorites/patch/${favorite.id}`, payload);
```

<br>

#### 5. `apiDelete(path, body, opts)`
Runs a **DELETE** request.

**Important behavior**
- Some APIs accept a body on DELETE, some don't.
- This helper **only includes a body if you pass one**.

**Example (no body)**
```js
await apiDelete(`/api/favorites/${id}`);
```

**Example (body allowed by server)**
```js
await apiDelete('/api/favorites', { ids: [1, 2, 3] });
```

<br>

### B. Options (opts) Shared by All Helpers <a id="options"></a>
Each helper accepts an `opts` object with:

`opts.query`

Key/value pair appended to the URL as a query string.
- Supports strings, numbers, booleans
- Arrays are allowed in the type definition, but note: `URLSearchParams(query)` does not automatically expand arrays the way some libs do. If you pass arrays, they’ll typically stringify (implementation-dependent). If you need repeated keys (e.g. `?id=1&id=2`), consider pre-building the query string or enhancing this helper

**Example**
```js
apiGet('/api/search', { query: { q: 'cloud', page: 2 } });
```

`opts.headers`

Extra headers merged into the request headers.
Common usage:
- CSRF token headers
- Custom authentication headers (if applicable)
- Content negotiation

**Example**
```js
apiPost('/api/items', { title: 'Book' }, {
  headers: { 'X-CSRF-Token': getCsrf() }
});
```

`opts.signal`

An `AbortSignal` to cancel the request.

This is especially useful with React hooks/effects to prevent "setState on unmounted component" warnings and race conditions.

<br>

### C. Error Helpers <a id="error-helper"></a>

`apiError(err)`

Produces a human-friendly message from an error object.

It checks common API error shapes in order:
1. `err.response.data.message`
2. `err.response.data.error`
3. `err.response.data.errors` (object of arrays, flattened)
4. Fallback: `err.message`
5. Final fallback: `"Something went wrong with your request."`

**Example**
```js
try {
    await apiPost('/api/items', payload);
} catch (err) {
    toast.error(apiError(err));
}
```

Note: `apiRequest` throws an `Error` annotated with `.status` and `.data`, and does **not** create an `err.response.data` structure. That shape is commonly associated with Axios. If you want `apiError()` to fully support `apiRequest()` errors, the best source is usually `err.data` / `err.status`. (You can  check those too by wrapping errors in your controller responses for consistently.)

<br>

### D. Core Client <a id="core-client"></a>
`apiRequest(method, path, opts)`

This is the implementation that all helpers call.

**What it does**
1. **Builds the URL**
    - Uses `new URL(path, window.location.origin)` so relative paths work.
    - If `opts.query` is provided, it appends it as a query string via `URLSearchParams`.
2. **Sends cookies automatically**
    - Uses `credentials: 'same-origin'` so session cookies are included.
    - This matches typical CSRF/session setups in PHP apps.
3. **Adds the “AJAX request” header**
    - Always includes `X-Requested-With: XMLHttpRequest`
    - Many server stacks use this to detect an AJAX request and return JSON errors.
4. **Determines whether to send a request body**
    - `GET` never sends a body.
    - `DELETE` only sends a body if you pass one.
    - Other methods send a body by default if provided.
5. **Encodes the body correctly**

    If the body is one of these “browser-managed” types:

    - `FormData`, `Blob`, `ArrayBuffer`, `URLSearchParams`, `ReadableStream`

    …then it **does not** set `Content-Type`, and it passes the body through so the browser sets headers properly (especially important for `FormData`).

6. **Parses JSON responses (when appropriate)**
- If the response is “no content” (`204`, `205`, `304`) or it was a `HEAD` request, it returns `{}`.
- Otherwise, it checks the `content-type` header and parses JSON if it looks like JSON.

7. **Throws consistent errors**
It throws an `Error` when:
- `res.ok` is false (non-2xx), or
- the JSON payload contains `{ success: false }`

Thrown errors are annotated:
- `err.status = res.status`
- `err.data = json`

This gives consumers a consistent way to handle failures:
```js
try {
    await apiPatch('/api/favorites/1', { is_home: true });
} catch (err) {
    console.log(err.status); // e.g. 422
    console.log(err.data);   // server payload (if JSON)
}
```

<br>

### E. React Hook <a id="react-hook"></a>
`useAsync(asyncFn, deps = [])`

A hook that runs an async function and manages state:
```js
{ data, loading, error }
```

**Why this exists**
React effects often need to:
- Fetch data when inputs change
- Show loading state
- Capture errors
- Cancel stale requests if the user changes inputs quickly or navigates away

`useAsync` provides a consistent pattern for that.

**How it works**
1. Initializes state
    - Starts with `{ data: null, loading: true, error: null }`

2. Creates an AbortController
    - Stored in a `useRef` so it persists across renders.

3. On every deps change
    - Aborts the previous request
    - Creates a brand new controller (fresh `signal`)
    - Resets state to loading
    - Executes `asyncFn({ signal })`

4. Prevents stale updates
    - Uses an `alive` flag so resolved promises can’t update state after cleanup.

5. Ignores abort errors
    - If the error is an `"AbortError"`, it is intentionally not stored in state.

**Example: using with your API helpers**
```js
const { data, loading, error } = useAsync(
    ({ signal }) => apiGet('/api/weather', { query: { q: city }, signal }),
    [city]
);

if (loading) return <Spinner />;
if (error) return <Alert>{apiError(error)}</Alert>;
return <Forecast data={data} />;
```

**Dependency note**
- useAsync disables the exhaustive-deps lint rule and uses `deps` directly. That means:
- You control exactly when it reruns.
- If you inline `asyncFn` and capture values, ensure `deps` includes what the function depends on.
- For best stability (especially in larger components), wrap `asyncFn` in `useCallback`.