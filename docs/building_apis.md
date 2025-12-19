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