<h1 style="font-size: 50px; text-align: center;">Using APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Configuration](#configuration)
3. [Service: OpenWeather client (server-side)](#service)
    * A. [Setting Up Our Service](#service-setup)
    * B. [Constructor](#constructor)
    * C. [Retrieving Data](#retrieving-data)
    * D. [Putting It All Together](#putting-it-all-together)
4. [Controller: API endpoint](#controller)
    * A. [Free tier API](#free-tier-api)
    * B. [OneCall API](#one-call-api)
    * C. [GeoLocation Data](#geo-location-data)
    * D. [Putting It All Together](#together)
5. [Router & ACL](#router)
6. [Front End](#front-end)
    * A. [Fetching Weather Data in React Using useAsync] (#fetching-data)
    * B [GeoLocate Data](#geo-locate-data)

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
use Core\Lib\Logging\Logger;
use Core\Lib\Http\Api;
/**
 * Service that supports retrieving weather from OpenWeatherMap.
 */
class WeatherService extends Api {

}
```

**TIP:** Debugging the back end when performing API calls can be difficult because calls to the global `dd()` and `dump()` do not get rendered in the front end when using React.js.  That is why we added an import to the `use Core\Lib\Logging\Logger` class.  Use the `Logger::log()` function to write your debugging details to the log file.

<br>

### A. Setting Up Our Service <a id="service-setup"></a>
Let's begin by declaring constants for the API endpoints that we will use:
```php
public const GEO_LOCATE = 'http://api.openweathermap.org/geo/1.0';
public const ONE_CALL = 'http://api.openweathermap.org/data/3.0';
public const STANDARD = 'http://api.openweathermap.org/data/2.5';
```

<br>

### B. Our Constructor <a id="constructor"></a>
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

### C. Retrieving Data <a id="retrieving-data"></a>
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

### D. Putting It All Together <a id="putting-it-all-together"></a>
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

We will need to make a controller that will handle interactions between the front end and back end.

Create the controller:
```sh
php console make:controller Weather
```

As shown below, we will need to import the `WeatherService` that we just created.  We will also need to import the `JsonResponse` trait and add `use JsonResponse` to the top of the class.

```php
<?php
namespace App\Controllers;

use Throwable;
use Core\Controller;
use App\Services\WeatherService;
use Core\Lib\Http\JsonResponse;
/**
 * Has actions that serves as endpoints for api requests.
 */
class WeatherController extends Controller {
    use JsonResponse;

    /**
     * OPTIONS /weather/*  (CORS preflight)
     *
     * @return void
     */
    public function preflightAction(): void {
        $this->preflight();
    }
}
```

<br>

### A. Free tier API <a id="free-tier-api"></a>

We will begin with adding support for the free API.  First we need to extract the required query parameters.
```php
$q = $this->request->get('q') ?? null;
$lat = $this->request->get('lat') ?? null;
$lon = $this->request->get('lon') ?? null;

if(!$q && !($lat && $lon)) {
    return $this->jsonError('Provide ?q=City or ?lat=&lon=', 422);
}
```

Next we create an instance of the WeatherService class
```php
$svc = new WeatherService();
```

This configures the service to use the **Free Tier API**, which provides data for the current weather conditions.

We will proceed to call the upstream OpenWeather free tier API and return the success JSON to the client.
```php
$data = $svc->current($this->request->get());

$this->jsonResponse(['success' => true, 'data' => $data]);
```

Finally, we need to handle any exceptions that may occur to indicate a failure happened upstream.  We do this by returning a `HTTP Bad Gateway` response.
```php
catch (\Throwable $e) {
    return $this->jsonError("Upstream error - {$e->getMessage()}", 502);
}
```

The complete function is shown below:
```php
/**
 * Supports query for getting current conditions for a given area.
 *
 * @return void
 */
public function currentConditionsAction() {
    try {
        $q = $this->request->get('q') ?? null;
        $lat = $this->request->get('lat') ?? null;
        $lon = $this->request->get('lon') ?? null;

        if(!$q && !($lat && $lon)) {
            return $this->jsonError('Provide ?q=City or ?lat=&lon=', 422);
        }

        $svc = new WeatherService();
        $data = $svc->current($this->request->get());

        $this->jsonResponse(['success' => true, 'data' => $data]);
    } catch(Throwable $e) {
        $this->jsonError("Upstream error - {$e->getMessage()}", 502);
    }
}
```

<br>

### B. OneCall API <a id="one-call-api"></a>
The process is similar for OneCall requests.  Extract the query parameters:
```php
$lat = $this->request->get('lat');
$lon = $this->request->get('lon');
```

Create a WeatherService instance but this time we will provide a parameter to tell the service we want OneCall data:
```php
$svc = new WeatherService(WeatherService::ONE_CALL);
```

This configures the service to use the **One Call API**, which provides:
- Current Weather
- Hourly forecast
- Daily forecast
- Alerts (where available)

A key difference in this function is we setup a parameters array:
```php
$params = [
    'lat'   => $lat,
    'lon'   => $lon,
    'units' => $this->request->get('units') ?? 'imperial',
    'lang'  => $this->request->get('lang')  ?? 'en',
];
```
Defaults:
- `units = imperial`
- `lang = en`

Just like before we need to contact the upstream server, return the JSON to the client.
```php
$data = $svc->oneCall($params);
return $this->jsonResponse(['success' => true, 'data' => $data]);
```

Finally, handle any exceptions that are encountered.
```php
catch(Throwable $e) {
    return $this->jsonError("Upstream error - {$e->getMessage()}", 502);
}
```

The completed function is as follows:
```php
/**
 * Performs API request for OneCall data and sends data back requestor.
 *
 * @return void
 */
public function oneCallAction() {
    try {
        $lat = $this->request->get('lat') ?? null;
        $lon = $this->request->get('lon') ?? null;

        if (!($lat && $lon)) {
            return $this->jsonError('Provide ?lat=&lon=', 422);
        }

        $svc = new WeatherService(WeatherService::ONE_CALL);

        $params = [
            'lat' => $lat,
            'lon' => $lon,
            'units' => $this->request->get('units') ?? 'imperial',
            'lang'  => $this->request->get('lang')  ?? 'en',
        ];

        $data = $svc->oneCall($params);
        return $this->jsonResponse(['success' => true, 'data' => $data]);
    } catch (\Throwable $e) {
        return $this->jsonError("Upstream error - {$e->getMessage()}", 502);
    }
}
```

<br>

### C. GeoLocation Data <a id="geo-location-data"></a>
We also want to provide a list of candidate locations while the user types into the search bar.  The function is as follows:
```php
/**
 * Performs query for geo location suggestions based on user input in 
 * search bar.
 *
 * @return void
 */
public function searchAction() {
    try {
        $q = $this->request->get('q') ?? null;
        if(!$q) {
            return $this->jsonError('Invalid location provided', 422);
        }
        
        $geo = new WeatherService(WeatherService::GEO_LOCATE);
        $data = $geo->geoLocation($this->request->get());
        $this->jsonResponse(['success' => true, 'data' => $data ?? '']);
    } catch(Throwable $e) {
        $this->jsonError('Upstream error', 502, ['detail' => $e->getMessage()]);
    }
}
```

Key differences:
- We return a response if an invalid location is provided.
- When creating an instance of the `WeatherService` class we use the `GEO_LOCATE` constant to select the correct API.

<br>

### D. Putting It All Together <a id="together"></a>
The complete class is shown below:
```php
<?php
namespace App\Controllers;

use Throwable;
use Core\Controller;
use App\Services\WeatherService;
use Core\Lib\Http\JsonResponse;
/**
 * Has actions that serves as endpoints for api requests.
 */
class WeatherController extends Controller {
    use JsonResponse;
    /**
     * Supports query for getting current conditions for a given area.
     *
     * @return void
     */
    public function currentConditionsAction() {
        try {
            $q = $this->request->get('q') ?? null;
            $lat = $this->request->get('lat') ?? null;
            $lon = $this->request->get('lon') ?? null;

            if(!$q && !($lat && $lon)) {
                return $this->jsonError('Provide ?q=City or ?lat=&lon=', 422);
            }

            $svc = new WeatherService();
            $data = $svc->current($this->request->get());

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch(Throwable $e) {
            return $this->jsonError("Upstream error - {$e->getMessage()}", 502);
        }
    }

    /**
     * Performs API request for OneCall data and sends data back requestor.
     *
     * @return void
     */
    public function oneCallAction() {
        try {
            $lat = $this->request->get('lat') ?? null;
            $lon = $this->request->get('lon') ?? null;

            if (!($lat && $lon)) {
                return $this->jsonError('Provide ?lat=&lon=', 422);
            }

            $svc = new WeatherService(WeatherService::ONE_CALL);

            $params = [
                'lat' => $lat,
                'lon' => $lon,
                'units' => $this->request->get('units') ?? 'imperial',
                'lang'  => $this->request->get('lang')  ?? 'en',
            ];

            $data = $svc->oneCall($params);
            return $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            return $this->jsonError("Upstream error - {$e->getMessage()}", 502);
        }
    }

    /**
     * OPTIONS /weather/*  (CORS preflight)
     *
     * @return void
     */
    public function preflightAction(): void {
        $this->preflight();
    }

    /**
     * Performs query for geo location suggestions based on user input in 
     * search bar.
     *
     * @return void
     */
    public function searchAction() {
        try {
            $q = $this->request->get('q') ?? null;
            if(!$q) {
                return $this->jsonError('Invalid location provided', 422);
            }
            
            $geo = new WeatherService(WeatherService::GEO_LOCATE);
            $data = $geo->geoLocation($this->request->get());
            $this->jsonResponse(['success' => true, 'data' => $data ?? '']);
        } catch(Throwable $e) {
            $this->jsonError('Upstream error', 502, ['detail' => $e->getMessage()]);
        }
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
For the front end we will rely on the framework's `api` utility.  The import statement is as follows:
```js
import { apiGet, useAsync } from '@chappy/utils/api';
```

<br>

### A. Fetching Weather Data in React Using useAsync <a id="fetching-data"></a>
The following example demonstrates how to fetch weather data inside a React component using the framework's built-in `useAsync` hook together with the API endpoints exposed by the backend (`/weather/currentConditions` and `/weather/oneCall`).

This pattern provides:
- Automatic loading and error tracking
- Safe cancellation 
- Dependency-based re-execution (similar to React's `useEffect`)

<br>

#### 1. Fetching Current Weather Conditions

```php
/**
 * Fetches data for current conditions using free tier API.
 */
const { 
    data: currentData, 
    loading: currentLoading, 
    error: currentError
} = useAsync(({ signal }) => {
        if (fetch == false) return Promise.resolve(null);
        if (!city) return Promise.resolve(null);

        const u = units || "imperial";
        return apiGet("/weather/currentConditions", { 
            query: { q: city, units: u }, 
            signal 
        });
}, [city, units]);

const current = currentData?.data || {};
const coords = current?.coord;
```

**Explanation**
1. useAsync wrapper
    The callback passed to `useAsync` is executed whenever the values in the dependency array (`city`, `units`) change.
2. Early exits
  - If fetch is disabled, the hook returns immediately.
  - If no city is provided, the request is skipped.
3. Calling the backend endpoint

    The hook calls your PHP controller at:
```bash
/weather/currentConditions?q={city}&units={units}
```
4. Automatic state handling 

    `useAsync` exposes:

    - `currentLoading` → true while waiting
    - `currentError` → contains error info if the request fails
    - `currentData` → normalized API response

5. Extracting coordinates

    The API returns the coord block from OpenWeatherMap.
    These coordinates are required for the next API call (One Call).
<br>

#### 2. Fetching Extended Forecast (One Call API)
```jsx
/**
 * Fetches data using oneCall api.
 */
const {
    data: oneCallData,
    loading: oneCallLoading,
    error: oneCallError,
} = useAsync(({ signal }) => {
    if (fetch == false) return Promise.resolve(null);

    if (!coords || typeof coords.lat !== "number" || typeof coords.lon !== "number") {
        return Promise.resolve(null);
    }

    const u = units || "imperial";
    return apiGet("/weather/oneCall", { 
        query: { lat: coords.lat, lon: coords.lon, units: u }, 
        signal 
    });
}, [coords?.lat, coords?.lon, units]);

const oneCall = oneCallData?.data || {};
```

**Explanation**
1. Runs only after coordinates are available

    The hook waits until coords.lat and coords.lon are valid numbers.
    This ensures the One Call API is not triggered prematurely.

2. Dependency-driven updates

    The hook re-runs when:
    - The latitude changes
    - The longitude changes
    - The selected temperature unit changes

3. Backend endpoint call

    The request is routed through:
```bash
/weather/oneCall?lat={lat}&lon={lon}&units={units}
```
    Your PHP backend then proxies this call to the OpenWeatherMap One Call API.

4. Returned data

    `oneCall` typically contains:
    - Current conditions
    - Hourly forecast
    - Daily forecast
    - Alerts (location-dependent)

5. Graceful error handling

    Any network or upstream failure is captured in `oneCallError`.
    The hook will safely cancel the request if the component unmounts.

<br>

#### 3. Summary

This pattern demonstrates how to chain API calls safely:
1. Fetch current conditions → extract coordinates
2. Use coordinates to fetch detailed forecast data
3. Handle loading and errors automatically
4. Prevent unnecessary or invalid API calls

The combination of `useAsync`, `apiGet()`, and your framework’s PHP controllers creates a clean and reliable workflow for consuming external APIs inside React.

<br>

### B. GeoLocate Data <a id="geo-locate-data"></a>
The following example demonstrates how the frontend performs a GeoLocation lookup based on a user’s search input. This request uses your backend’s /weather/search endpoint, which proxies the OpenWeatherMap Direct Geocoding API.

This feature is typically used for:
- auto-complete search bars
- selecting a location before fetching weather data
- validating city names before making additional API calls

<br>

#### 1. Setup
```jsx
/**
 * Performs API request for geo location data.
 */
const { data: options = [], loading, error } = useAsync(({ signal }) => {
    if (!q) return Promise.resolve([]);
    return apiGet('/weather/search', { query: { q }, signal })
}, [q]);

let geoData = options?.data;
```

**Explanation**

1. Triggered by user input

The hook runs whenever the search query q changes:
```jsx
useAsync(..., [q]);
```

This makes the geolocation lookup behave like a live search.

2. Prevents unnecessary API calls

If q is empty or undefined:

```jsx
if (!q) return Promise.resolve([]);
```

3. Calls the backend search endpoint

```jsx
apiGet('/weather/search', { query: { q }, signal })
```

This hits your PHP controller:

```bash
/weather/search?q={searchTerm}
```

Your backend then calls the OpenWeatherMap Direct Geocoding API and returns:
    - city name
    - state
    - country
    - latitude
    - longitude

4. Handles loading and error states automatically

From useAsync, the hook receives:
  - loading → true while fetching
  - error → error object if the request fails
  - options → normalized backend response

This keeps UI logic simple and consistent.

5. Normalizes returned data

```jsx
let geoData = options?.data;
```

Your backend wraps results inside a `data` property, so this line extracts the actual results array.

`geoData` typically contains multiple matching locations, such as:
```json
[
  {
    "name": "Richmond",
    "state": "Virginia",
    "country": "US",
    "lat": 37.5407,
    "lon": -77.4360
  }
]
```

These entries can then populate a dropdown or pass coordinates to the next API call.

<br>

#### 2. Summary
1. This hook provides a clean and efficient way to search for cities:
2. Runs only when the user types something
3. Avoids wasted API calls for empty input
4. Queries the backend for geolocation data
5. Uses built-in loading/error state handling
6. Delivers a list of matching cities for UI components

It's a simple but powerful example of using useAsync to build a reactive, API-driven front end.