<h1 style="font-size: 50px; text-align: center;">Building APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Routing](#routing)
3. [Building The API End Points](#end-points)
    * A. [Create](#create)
    * B. [Read](#read)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The built-in API library can be utilized to build your own API.  Just like regular actions, we will leverage the framework's DB and Model classes to perform Create, Read, Update, and Delete (CRUD) operations.  When you build your own API you use the project's API to perform operations instead of reaching out to an external service.

<br>

## 2. Routing <a id="routing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Before we implement anything we need to setup or routing.

```json
"LoggedIn" : {
    "denied" : {
        "Auth" : ["login", "register", "resetPassword"]
    },
    "Auth" : ["logout"],
    "Contacts" : ["*"],
    "Profile" : ["*"],
    "Favorites" : ["store", "show", "destroy", "patch"]
},
```

In the json snippet above we added a new section that will match the name of our controller.  Since we want this feature to be available only to logged in users we set our routes inside the LoggedIn section.  The name of our routs will be `store`, `show`, `destroy`, and `patch`.

<br>

## 3. Building The API End Points <a id="end-points"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When building API End Points we will leverage a Controller class and Model class.  This discussion will leverage code from a weather app's favorites features for demonstration purposes.  

First we need to create a model:
```php
php console make:migration
```

Add the following to our migration:
```php
public function up(): void {
    Schema::create('favorites', function (Blueprint $table) {
        $table->id();
        $table->string('name', 150);
        $table->float('latitude');
        $table->float('longitude');
        $table->integer('user_id');
        $table->index('user_id');
        $table->tinyInteger('is_home');
        $table->softDeletes();
    });
}
```

Now that we have a migration we will create our model:
```php
php console make:model Favorites
```

Constants and instance fields are as follows:
```php
// Fields you don't want saved on form submit
public const blackList = ['deleted', 'id'];

// Set to name of database table.
protected static $_table = 'favorites';

// Soft delete
protected static $_softDelete = true;

// Fields from your database
public $deleted = 0;
public $id;
public $is_home = 0;    // Tracks if location is user's home
public $latitude;
public $longitude;
public $name;           // Location name
public $user_id;        // User associated with location
```

We will also need a static function to return the current home location:
```php
public static function findCurrentHome(int $user_id) {
    $conditions = [
        'conditions' => 'user_id = ? AND is_home = ?',
        'bind' => [(int)$user_id, 1]
    ];
    
    return self::findFirst($conditions);
} 
```

Next, we need to create a controller.  Let's begin by running the following command:

```bash
php console make:controller Favorites
```

The new `FavoritesController` appears at `app/Controllers`.  You can remove the `onConstruct` function that is created for setting the layout since this controller will not be responsible for returning views.  We will need to import and use the `JsonResponse` trait as shown below.

```php
<?php
namespace App\Controllers;
use Core\Controller;
use Core\Lib\Http\JsonResponse;
/**
 * Undocumented class
 */
class FavoritesController extends Controller {
    use JsonResponse;

    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void {
        $this->view->setLayout('default');
    }
}
```

<br>

### A. Create  <a id="create"></a>
This section we will discuss what is needed to retrieve records from our API and present the data to the user.

In our FavoritesController we will create the following function:
```php
public function storeAction() {
    try {
        if(!$this->apiCsrfCheck()) {
            return $this->jsonError('Corrupted token');
        }

        $favorite = new Favorites();
        $favorite->assign($this->get());
        $favorite->user_id = AuthService::currentUser()->id;
        $favorite->save();
    } catch (Throwable $e){
        return $this->jsonError('Server error', 500);
    }
}
```

This function performs the following tasks:
- We perform a CSRF check since we will be submitting a form.  
- Create a new `Favorites` object and use the `get()` from the `JsonResponse()` trait to retrieve data from our front end.  This is similar to using the `$this->request->get` for  PHP views.  
- Set `user_id` to id of current user.
- Save the record.
- Catch any exceptions and return jsonError response.

Next we create our form:
```jsx
<form method="POST" onSubmit={handleSubmit}>
    <Forms.CSRFInput />
    <Forms.Hidden name="city" value={weather.getCityInfo()} />
    <Forms.Hidden name="latitude" value={weather.getLatitude()} />
    <Forms.Hidden name="longitude" value={weather.getLongitude()} />
    <button 
        type="submit" 
        className="btn btn-primary btn-sm mt-1">
        <i className="me-2 fa fa-plus"></i>Add
    </button>
</form>
```

This form performs the following steps:
- Reference the `handleSubmit` callback by setting it as the value for the `onSubmit` attribute.
- Add CSRFInput hidden element.
- Use hidden elements to send the name of the city, latitude, and longitude to the backend.  This form needs no input fields for this task.

We now need to handle submission of the form as shown below:
```jsx
async function handleSubmit(e) {
    const storedWeather = weather.readStorage();
    e.preventDefault();
    try {
        const payload = {
            name: storedWeather.location,
            latitude: e.target.latitude.value,
            longitude: e.target.longitude.value,
            csrf_token: Forms.CSRFToken(e)
        }
        const json = await apiPost("/favorites/store", payload);
        window.location.reload();
    } catch (err) {
        setError(apiError(err));
    }    
}
```

This function performs the following steps:
- Tell the user agent that the event is being explicitly handled.
- Inside the try block we ned to setup the payload.  We need to send the location's name, latitude, longitude, and CSRF as part of our request.
- Call the apiPost function with our route and payload as our parameters.
- Reload the window when we successfully submit our request.
- Catch any errors and present them to the user.

<br>

### B. Create  <a id="read"></a>