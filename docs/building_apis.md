<h1 style="font-size: 50px; text-align: center;">Building APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [Building The API End Points](#end-points)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The built-in API library can be utilized to build your own API.  Just like regular actions, we will leverage the framework's DB and Model classes to perform Create, Read, Update, and Delete (CRUD) operations.  When you build your own API you use the project's API to perform operations instead of reaching out to an external service.

<br>

## 2. Building The API End Points <a id="end-points"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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