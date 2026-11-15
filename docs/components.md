---
layout: default
title: "Components"
parent: "Views Introduction"
nav_order: 2
---

# Components
## Table of contents
1. [Overview](#overview)
2. [Card Component](#card)
3. [Form Component](#form)
4. [Table Component](#table)

<br>

## 1. Overview <a id="overview"></a>
Components are blocks of code that can be reused between different views or multiple times within the same view.  Running the `make:component` command generates a new component at `resources/views/components`.

<br>

## 2. Card Component<a id="card"></a>
Running the following,

```sh
php console make:component my_card --card,
```

generates a card component.

<br>

## 3. Form Component<a id="form"></a>
You can create a form by running the following,

```sh
php console make:component my_form --form
```

After running the command you will be asked a series of questions to determine which 'method' and 'enctype' you want to use.

<br>

## 4. Table Component<a id="table"></a>
Running the following,

```sh
php console make:component my_table --table,
```

generates a table component.