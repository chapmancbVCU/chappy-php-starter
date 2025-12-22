<h1 style="font-size: 50px; text-align: center;">Building APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Routing](#routing)
3. [Building The API End Points](#end-points)
    * A. [Create](#create)
    * B. [Read](#read)
    * C. [Update](#update)
    * D. [Delete](#delete)
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

We will also need 2 static functions.  The function below returns a record for a favorite that is the current home location.
```php
public static function findCurrentHome(int $user_id) {
    $conditions = [
        'conditions' => 'user_id = ? AND is_home = ?',
        'bind' => [(int)$user_id, 1]
    ];
    
    return self::findFirst($conditions);
} 
```

The next function returns a Favorite record based on it's `$id` and `$user_id` fields:
```php
public static function findByIdAndUserId(int $id, int $user_id): object|bool {
    return self::findFirst(
        [
        'conditions' => 'id = ? AND user_id = ?',
        'bind' => [(int)$id, (int)$user_id]
    ]);
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

This example uses the `apiPost` and `useAsync` utilities, which are documented in the **API Utility and JsonResponse Trait** section.

<br>

### B. Read  <a id="read"></a>
The read operation is pretty straight forward.  We implement our action, perform an `apiGet` function call, and render the data.  In this section the favorites will be displayed as a series of cards.

The controller's action is pretty straight forward.  We determine who is the logged in user, find all their favorites, and return the response as shown below:

```php
public function showAction(): void {
    $user = AuthService::currentUser();
    $data = Favorites::findAllByUserId($user->id);
    $this->jsonResponse(['success' => true, 'data' => $data]);
}
```

We will use a hook that we will import into our views to retrieve the data.

```jsx
import { useAsync, apiGet } from "@chappy/utils/api";

/**
 * Obtains list favorites using the showAction of the FavoritesController.
 * @returns 
 */
const useFavorites = () => {
    const { data, loading, error } = useAsync(({ signal }) => 
            apiGet('/favorites/show', { signal }))
    
    let favorites = data?.data;

    return { favorites }
}

export default useFavorites;
```

This hook retrieves a list of favorites from the back end using the **FavoritesController** `showAction` endpoint.
- The API request is executed automatically when the component using this hook is mounted.
- The response data is normalized and exposed as `favorites`.
- The components consuming this hook do **not** need to be aware of how the API call is implemented.

This example uses the `apiGet` and `useAsync` utilities, which are documented in the **API Utility and JsonResponse Trait** section.

Finally, were present the data to the user.
```jsx
function Favorites({ favorites, units }) {
    const isDataNotEmpty = favorites && Object.keys(favorites).length > 0;

    return (
        <>
            {isDataNotEmpty && (
                <div className="favorites-bar mx-auto my-3">
                    <h5 className="text-center mt-4">Favorite Locations</h5>
                    <div className="favorite-cards-container">
                        {favorites && favorites.map((favorite, index) => (
                            <FavoritesCard favorite={favorite} key={index} units={units}/> 
                        ))}
                    </div>
                </div>
            )}
        </>
    );
}  
```

<br>

### C. Update  <a id="update"></a>
The update operation is similar to the create operation.  In the example below the main difference is we check if a home location already exists.  If a home location exists we set the `is_home` field to 0 and update the record.  Next, we retrieve the record based on the `$id` parameter then set it's `is_home` field to 1.  Finally, we save that record.  

```php
public function patchAction(int $id) {
    try {
        if(!$this->apiCsrfCheck()) {
            return $this->jsonError('Corrupted token');
        }
        $user = AuthService::currentUser();
        $currentHome = Favorites::findCurrentHome($user->id);

        if($currentHome && $currentHome->is_home == 1) {
            $currentHome->is_home = 0;
            $currentHome->save();
        }

        $favorite = Favorites::findByIdAndUserId($id, $user->id);
        if($favorite) {
            $favorite->is_home = 1;
            $favorite->save();
        }
    } catch (Throwable $e){
        return $this->jsonError('Server error' . $e, 500);
    }
}
```

The form that we will use is as follows:
```jsx
<form method="POST" onSubmit={handleSubmit}>
    <Forms.CSRFInput />
    <button 
        type="submit" 
        className="btn btn-primary btn-sm mt-1">
        <i className="me-2 fa fa-home"></i>Set Home
    </button>
</form>
```

There is no need to have any input fields besides the CSRF token.  The only data that we need to send is the `$id` of the currently selected favorite.  We accomplish this as a parameter for our route as shown below:
```jsx
async function handleSubmit(e) {
    const storedWeather = weather.readStorage();
    e.preventDefault();
    try {
        const payload = {
            csrf_token: Forms.CSRFToken(e)
        }
        const json = await apiPatch(`/favorites/patch/${favorite.id}`, payload);
        window.location.reload();
    } catch (err) {
        setError(apiError(err));
    }
}
```

This example uses the `apiPatch` and `useAsync` utilities, which are documented in the **API Utility and JsonResponse Trait** section.

<br>

### D. Delete  <a id="delete"></a>
The controller action for the delete operation is shown below.
```php
public function destroyAction(int $id) {
    try {
        if(!$this->apiCsrfCheck()) {
            return $this->jsonError('Corrupted token');
        }
        
        $user = AuthService::currentUser();
        $favorite = Favorites::findByIdAndUserId($id, $user->id);
        if($favorite) {
            $favorite->delete();
        }
    } catch (Throwable $e){
        return $this->jsonError('Server error', 500);
    }
}
```

This function performs the following tasks:
- CSRF check.
- Find Favorite record using ID provided.
- Performs delete operation.
- Report any errors that may exist.

The form is pretty straight forward.  No data is sent as part of request except for the CSRF token by the form.
```jsx
<form method="POST" onSubmit={onDeleteClick}>
    <Forms.CSRFInput />
    <button 
        type="submit" 
        className="btn-danger delete-favorite">
        <i className="fa fa-times"></i>
    </button>
</form>
```

The onDeleteClick callback is shown below.
```jsx
async function onDeleteClick(e) {
    e.preventDefault()
    if(window.confirm(`Are you sure you want to delete the location ${favorite.name}?`)) {
        try {
            const payload = {
                csrf_token: Forms.CSRFToken(e)
            }
            const json = await apiDelete(`/favorites/destroy/${favorite.id}`, payload);
            window.location.reload();
        } catch (err) {
            setError(apiError(err));
        }
    }
}
```

The above function sends the CSRF token as the payload and uses the `id` for the favorite record as a parameter in the path.

This example uses the `apiDelete` and `useAsync` utilities, which are documented in the **API Utility and JsonResponse Trait** section.