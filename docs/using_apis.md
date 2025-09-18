<h1 style="font-size: 50px; text-align: center;">Using APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Configuration](#configuration)
3. [Service: OpenWeather client (server-side)](#service)
4. [Controller: API endpoint](#controller)
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

New file is created at `app\Services`.

Implement the `WeatherService` class:
```php
<?php
declare(strict_types=1);

namespace App\Services;

use Core\Lib\Utilities\Env;
use Core\Lib\Http\Api;
class WeatherService extends Api {
    public function __construct()
    {
        parent::__construct(
            baseUrl: 'https://api.openweathermap.org/data/2.5',
            cacheNamespace: 'owm',
            defaultHeaders: ['Accept' => 'application/json'],
            defaultQuery: [
                'appid' => Env::get('OWM_API_KEY'),
                // Set a default; client can override via ?units=metric|imperial
                'units' => 'imperial',
            ],
            defaultTtl: 120,  // seconds to cache GETs
            timeout: 6
        );
    }

    /**
     * Current conditions by free-form query 'q' (e.g., city,country).
     * Supports 'q', 'units', 'lang', or lat/lon parameters.
     */
    public function current(array $query): array
    {
        // Allow q=, zip=, or lat/lon; pass through units/lang if present
        $allowed = ['q', 'zip', 'lat', 'lon', 'units', 'lang'];
        $params  = array_intersect_key($query, array_flip($allowed));

        return $this->get('/weather', $params); // cached via Api::get
    }
}
```

Make sure you import `Core\Lib\Http\Api` and extend the `Api` class.

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