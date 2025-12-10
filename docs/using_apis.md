<h1 style="font-size: 50px; text-align: center;">Using APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Configuration](#configuration)
3. [Service: OpenWeather client (server-side)](#service)
4. [Controller: API endpoint](#controller)
5. [Router & ACL](#router)
6. [Front End](#front-end)
7. [Test](#test)
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

<br>

## 3. Service: OpenWeather client (server-side) <a id="service"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Run the command:
```sh
php console make:service WeatherService
```

New file is created at `app\Services`.  In this class we will add support for OpenWeatherMap's free, OneCall, and GeoLocate APIs.

Begin implementation of the `WeatherService` class by importing `Core\Lib\Http\Api` and extend the `Api` class.
```php
<?php
namespace App\Services;
use Core\Lib\Http\Api;
/**
 * Service that supports retrieving weather from OpenWeatherMap.
 */
class WeatherService extends Api {

}
```

<br>

### A. Setting Our API Endpoints.
Let's begin by declaring constants for the API endpoints that we will use:
```php
public const GEO_LOCATE = 'http://api.openweathermap.org/geo/1.0';
public const ONE_CALL = 'http://api.openweathermap.org/data/3.0';
public const STANDARD = 'http://api.openweathermap.org/data/2.5';
```

<br>

### B. Our Constructor
Next we implement the constructor:
```php
/**
     * Setup instance of this class.  Configures default query with 
     * appid and units for suggestions to be returned.
     */
    public function __construct(string $mode = self::STANDARD) {
        
        self::isValidMode($mode);
        parent::__construct(
            baseUrl: $mode,
            cacheNamespace: 'owm',
            defaultHeaders: ['Accept' => 'application/json'],
            defaultQuery: self::buildQuery($mode),
            defaultTtl: 120,
            timeout: 6
        );
    }
```

The parent class constructor accepts the following arguments:
- `$baseUrl` - The service's base url.  We use one of 3 constants that we declared above as parameter for the this class' constructor to set value.  
- `$cacheNamespace` - Subdirectory under cache root.  Default value is `api`.
- `$defaultHeaders` - Default headers for all requests.  Default value is `['Accept' => 'application/json']`.
- `$defaultQuery` - Default query params for all requests.  Default value is an empty array.
- `$defaultTtl` - Default cache TTL (seconds) for GET; 0 disables caching.  Default value is `0`.
- `$timeout` - cURL timeout in seconds (also used as a connect timeout).  Default value is `0`.

This constructor uses two helper functions:
- `isValidMode()` - Test to ensure a supported mode is being set.
- `buildQuery()` - Builds defaultQuery based on which API is selected.

These functions are as follows:
```php
/**
 * Builds defaultQuery based on which API is selected.
 *
 * @param string $mode The specific API to use.
 * @return array $query The params for the defaultQuery.
 */
private static function buildQuery(string $mode): array {
    $query = [];
    $query['appid'] = env('OWM_API_KEY');
    // $query['units'] = 'imperial';

    if($mode === self::GEO_LOCATE) {
        $query['limit'] = env('OWM_SEARCH_TERM_LIMIT');
    }

    return $query;
}

/**
 * Determines if mode provided in constructor is valid value
 *
 * @param string $mode The mode that determines appropriate API call.
 * @return void
 */
private static function isValidMode(string $mode): void {
    if(!in_array($mode, [self::GEO_LOCATE, self::ONE_CALL, self::STANDARD])) {
        throw new InvalidArgumentException("Invalid api call: $mode");
    }
}
```

<br>

### C. Retrieving Data
Below is the function that fetches data from their free API.
```php
/**
 * Packages query for current conditions using free tier api call.
 *
 * @param array $query The query string
 * @return array The response data for the API request containing 
 * weather information.
 */
public function current(array $query): array {
    $allowed = ['q', 'units', 'lang'];
    $params = array_intersect_key($query, array_flip($allowed));
    return $this->get('/weather', $params);
}
```

The `$query` parameter contains data packaged together on the front end.  The query will contain information used to build the URl for the fetch request.  We want to ensure the request contains data that is allowed.  For this function we allow the query string (`q`), the system of units, and the preferred language.

Next we package the `$query` into a $params array that is provided as a parameter to th Api class' `get` function.  The `get` function will perform the fetch request to OpenWeatherMap for us.

An example of the complete URL that gets submitted is shown below:
```sh
https://api.openweathermap.org/data/2.5/weather?lat=37.5407&lon=-77.4360&units=imperial&appid=YOUR_API_KEY
```

The operation for OneCall and GeoLocation is similar.  For GeoLocation we only need to be concerned about the query string.  OneCall is a little more complex regarding the contents of the `$allowed` array.  Both functions are shown below:
```php
/**
 * Packages query for geo location based on user input.
 *
 * @param array $query The query string.
 * @return array The response data for the API request.
 */
public function geoLocation(array $query): array {
    $allowed = ['q'];
    $params = array_intersect_key($query, array_flip($allowed));
    return $this->get('/direct', $params);
}

/**
 * Packages query for onecall api call.
 *
 * @param array $query The query string.
 * @return array The response data for the API request.
 */
public function oneCall(array $query): array {
    $allowed = ['lat', 'lon', 'units', 'lang', 'exclude'];
    $params = array_intersect_key($query, array_flip($allowed));
    return $this->get('/onecall', $params);
}
```

<br>

### D. Putting It All Together
The complete class is shown below:
```php
<?php
namespace App\Services;

use Core\Lib\Http\Api;
use Core\Lib\Logging\Logger;
use InvalidArgumentException;

/**
 * Service that supports retrieving weather from OpenWeatherMap.
 */
class WeatherService extends Api {
    public const GEO_LOCATE = 'http://api.openweathermap.org/geo/1.0';
    public const ONE_CALL = 'http://api.openweathermap.org/data/3.0';
    public const STANDARD = 'http://api.openweathermap.org/data/2.5';
    /**
     * Setup instance of this class.  Configures default query with 
     * appid and units for suggestions to be returned.
     */
    public function __construct(string $mode = self::STANDARD) {
        
        self::isValidMode($mode);
        parent::__construct(
            baseUrl: $mode,
            cacheNamespace: 'owm',
            defaultHeaders: ['Accept' => 'application/json'],
            defaultQuery: self::buildQuery($mode),
            defaultTtl: 120,
            timeout: 6
        );
    }

    /**
     * Builds defaultQuery based on which API is selected.
     *
     * @param string $mode The specific API to use.
     * @return array $query The params for the defaultQuery.
     */
    private static function buildQuery(string $mode): array {
        $query = [];
        $query['appid'] = env('OWM_API_KEY');
        // $query['units'] = 'imperial';

        if($mode === self::GEO_LOCATE) {
            $query['limit'] = env('OWM_SEARCH_TERM_LIMIT');
        }

        return $query;
    }

    /**
     * Packages query for current conditions using free tier api call.
     *
     * @param array $query The query string
     * @return array The response data for the API request containing 
     * weather information.
     */
    public function current(array $query): array {
        $allowed = ['q', 'units', 'lang'];
        $params = array_intersect_key($query, array_flip($allowed));
        return $this->get('/weather', $params);
    }

    /**
     * Packages query for geo location based on user input.
     *
     * @param array $query The query string.
     * @return array The response data for the API request.
     */
    public function geoLocation(array $query): array {
        $allowed = ['q'];
        $params = array_intersect_key($query, array_flip($allowed));
        return $this->get('/direct', $params);
    }

    /**
     * Packages query for onecall api call.
     *
     * @param array $query The query string.
     * @return array The response data for the API request.
     */
    public function oneCall(array $query): array {
        $allowed = ['lat', 'lon', 'units', 'lang', 'exclude'];
        $params = array_intersect_key($query, array_flip($allowed));
        return $this->get('/onecall', $params);
    }

    /**
     * Determines if mode provided in constructor is valid value
     *
     * @param string $mode The mode that determines appropriate API call.
     * @return void
     */
    private static function isValidMode(string $mode): void {
        if(!in_array($mode, [self::GEO_LOCATE, self::ONE_CALL, self::STANDARD])) {
            throw new InvalidArgumentException("Invalid api call: $mode");
        }
    }
}
```

<br>

## 4. Controller: API endpoint <a id="controller"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Create the controller:
```sh
php console make:controller Weather
```

Import `App\Services\WeatherService` and implement the `showAction`.

```php
<?php
namespace App\Controllers;
use Throwable;
use Core\Controller;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    public function showAction()
    {
        try {
            $q    = $_GET['q']   ?? null;
            $lat  = $_GET['lat'] ?? null;
            $lon  = $_GET['lon'] ?? null;

            if (!$q && !($lat && $lon)) {
                return $this->jsonError('Provide ?q=City or ?lat=&lon=', 422);
            }

            $svc  = new WeatherService();
            $data = $svc->current($_GET);

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            $this->jsonError('Upstream error', 502, ['detail' => $e->getMessage()]);
        }
    }

    // OPTIONS /api/weather/* (CORS)
    public function preflightAction(): void
    {
        $this->preflight();
    }
}
```

<br>

## 5. Router & ACL <a id="router"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Router

Your router maps /{Controller}/{action}. Two common choices:
- **Option A** (as written): call `GET /api/weather/show?q=...`
- **Option B**: make indexAction and call `GET /weather?q=...`

Use whichever you prefer—your current style is fine.

**ACL** (app/acl.json)
Let Guests read weather; restrict writes if needed.
```json
{
  "Guest": {
    "Weather": ["show", "preflight"]
  },
  "LoggedIn": {
    "Weather": ["show", "preflight"]
  },
  "denied": {}
}
```

<br>

## 6. Front End <a id="front-end"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Make a new component:
```sh
php console react:component WeatherCard
```

The file is created at `resources\js\components\`.

Import `apiGet` and `useAsync` from `@chappy/utils/api` then implement the `WeatherCard`.

```jsx
import React from 'react';
import { apiGet, useAsync } from '@chappy/utils/api';

export default function WeatherCard({ city = 'Newport News, Virginia', units = 'imperial' }) {
  const { data, loading, error } = useAsync(({ signal }) =>
    apiGet('/weather/show', { query: { q: city, units }, signal }),
  [city, units]);

  if (loading) return <div>Loading…</div>;
  if (error)   return <div className="text-danger">{error.message}</div>;

  const d = data?.data || {};
  return (
    <div className="card p-3">
      <h5 className="mb-2">{d.name}</h5>
      <div>
        {Math.round(d.main?.temp)}°{units === 'metric' ? 'C' : 'F'} — {d.weather?.[0]?.description}
      </div>
    </div>
  );
}
```

Mount from a PHP host view the same way you mount other React pages (via your `app.jsx` entry + `data-component/data-props`).

<br>

## 7. Test <a id="test"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
**Browser/React**
Open a page that renders WeatherCard.

**cURL / Postman**
```bash
curl "http://localhost:8000/api/weather/show?q=Austin,TX&units=imperial"
```

**Example error**
```json
{
  "success": false,
  "message": "Provide ?q=City or ?lat=&lon=",
  "errors": []
}
```