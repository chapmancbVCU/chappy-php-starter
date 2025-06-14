<h1 style="font-size: 50px; text-align: center;">Routing</h1>

## Table of contents
1. [Overview](#overview)
2. [How It Works](#how-it-works)
3. [Default Conventions](#conventions)
4. [Fallbacks](#fallbacks)
5. [No Route File? Why?](#no-route)
6. [Pros and Cons](#pros-cons)
7. [Redirect](#redirect)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
📘 Routing System (Dynamic Routing)
Unlike many modern PHP frameworks that use a route definition file (e.g., routes/web.php), Chappy.php uses dynamic routing. This means routes are automatically resolved based on the URL structure and available controllers/methods—no manual route registration required.

<br>

## 2. 🧠 How It Works <a id="how-it-works"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Router class parses incoming requests and maps them directly to controller methods using the URL pattern:

```swift
/controller/method/optional/params
```

```perl
For example:
| URL                    | Resolved Method                          |
|------------------------|-------------------------------------------|
| `/home/index`          | `HomeController::index()`                |
| `/user/profile/42`     | `UserController::profile(42)`            |
| `/auth/login`          | `AuthController::login()`                |

```

If the URL is `/`, it defaults to:
```php
HomeController::index()
```

<br>

## 3. 🏗 Default Conventions <a id="conventions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Controller classes must be located in `app/Controllers/`.
- Controller names should end with` Controller` (e.g., `UserController`).
- Method names in controllers map 1:1 with URL segments.
- Additional URL segments are passed as arguments to the controller method.

<br>

## 4. 🛡 Fallbacks <a id="fallbacks"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
If a controller does not exist the user is redirected to a view indicating the issue.  When an action does not exist then whoops displays an error indicating the function does not exist in the controller.

<br>

## 5. 🚫 No Route File? Why? <a id="no-route"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php uses dynamic routing to keep your application lightweight, fast, and convention-driven, eliminating the need to register every route manually.

- This pattern is ideal for:
- Small to medium-sized applications
- Developers who prefer convention over configuration
- Rapid prototyping and reduced boilerplate

<br>

## 6. Pros and Cons <a id="pros-cons"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
✅ Pros
- No route files to maintain
- Easy to follow MVC conventions
- Clear structure: URL = Controller → Method

⚠️ Considerations
- You can’t assign route names or middleware per route (yet)
- Custom route aliases or regex pattern matching aren't supported out of the box
- You should avoid duplicate method names across controllers that could cause confusion

<br>

## 7. Redirect <a id="redirect"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function comes with a static redirect function that can be called in two different ways.
- `Router::redirect('admindashboard/details/' . $user->id)` - Directly calls static function
- `redirect('admindashboard/details/' . $user->id)` - Shorthand publicly available global function