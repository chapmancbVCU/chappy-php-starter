<h1 style="font-size: 50px; text-align: center;">Controllers and React Views</h1>

## Table of contents
1. [Overview](#overview)
2. [Passing in Props](#passing-in-props)
3. [View Commands](#view-commands)




<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Just like PHP views, configuring your React.js views occurs within your controllers.  To facilitate this we have a `renderJsx` function and a $this->view->props variable.

<br>

## 2. Passing in Props <a id="opassing-in-props"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Below is the PHP version of this process:

```php
$this->view->user = AuthService::currentUser() ?? 'Guest';
$this->view->render('home.index');
```

The React.js version is shown below:
```php
// In your controller:
$props = ['user' => ['name' => 'Chad']];
$this->view->renderJsx('home.Index', $props); // maps to resources/js/pages/home/Index.jsx
```

or 

```php
$this->view->props = ['user' => ['name' => 'Chad']];
$this->view->renderJsx('home.Index');
```

Added each props object as a parameter:
```jsx
export default function Index({ user }) {
    const name = user.fname ?? 'Guest';

    return (
        <>
            <h1>Hello {name}</h1>
        </>
    );
}
```

<br>

## 3. View Commands <a id="view-commands"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support several commands for managing views and layouts with React.

**Layouts**
The default layout supports React integration.  To create a support layout run:
```sh
php console make:layout <layout_name> --react
```

**Views**
Generate a React view under `resources/js/pages/<area>/<view_name>.jsx`:
```sh
php console react:page <area>.<view_name>
```

**Generating Built-in Views**

| Command | Description |
|:-------:|-------------|
| `php console react:auth` | Generates page components for the auth controller |
| `php console react:error` | Restores error/NotFound.jsx page component |
| `php console react:home` | Generates Index.jsx page component for the home controller |
| `php console react:profile` | Generates page components for the profile controller | 