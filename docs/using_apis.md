<h1 style="font-size: 50px; text-align: center;">Using APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Configuration](#configuration)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support RESTful API requests to API providers.  In this guide we will build a tiny API endpoint that does server-side fetch to OpenWeatherMap (OWM) and returns safe JSON to your React app.
- Client calls: `GET /weather/show?q=Newport News, Virginia&units=imperial`
- Server fetches from OWM, caches the response (TTL), normalizes errors, and returns JSON.
- React renders a `WeatherCard` using `useAsync` + `apiGet`.

Why proxy?
- You hide your **OWM API** key on the server.
- You control caching, validation, and error shape.
- You avoid CORS/key leakage issues.

<br>

## 2. Configuration <a id="configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Add your OWM key to .env (server only):
```ini
OWM_API_KEY=your_openweather_key_here
```
(Do not expose this key to Vite or the browser.)