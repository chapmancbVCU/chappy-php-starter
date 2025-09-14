<h1 style="font-size: 50px; text-align: center;">Controllers and React Views</h1>

## Table of contents
1. [Overview](#overview)
2. [Passing in Props](#passing-in-props)





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