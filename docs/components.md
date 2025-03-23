<h1 style="font-size: 50px; text-align: center;">Components</h1>

## Table of contents
1. [Overview](#overview)
2. [Card Component](#card)
3. [Form Component](#form)
4. [Table Component](#table)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Components are blocks of code that can be reused between different views or multiple times within the same view.  Running the `make:component` command generates a new component at `resources/views/components`.
<br>

## 2. Card Component<a id="card"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Running the following,

```sh
php console make:component my_card --card,
```

generates a card component.
<br>

## 3. Form Component<a id="form"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can create a form by running the following,

```sh
php console make:component my_form --form
```

There are 2 flags that you can set as described below:
1. `--form-method=<method_name>` - Sets the method for the form with a default as `post`.
2. `--enctype=<mime_type>` - Sets the enctype with `application/x-www-form-urlencoded` as the default even if attribute is not listed in form element.  Use `multipart/form-data` for file uploads and `text/plain` for ambiguous format, human-readable content not reliably interpretable by computer.
<br>

## 4. Table Component<a id="table"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Running the following,

```sh
php console make:component my_table --table,
```

generates a table component.