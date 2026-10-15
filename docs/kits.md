<h1 style="font-size: 50px; text-align: center;">Development Experience (Kits)</h1>

## Table of contents
1. [Overview](#overview)
2. [Additional Commands](#additional-commands)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php supports a pure PHP or React.js or hybrid experience.  Run the following commands to get setup:
- `kit:php` - Generates views, components, and controllers for a PHP experience
wing commands to get setup:
- `kit:react` - Generates views, components, and controllers for a React.js experience

If neither is used then the framework defaults to built-in controllers, views, and components.

<br>

## 2. Additional Commands <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#additional-commands)</span>
Build your experience piece-meal with the commands described in the table below:

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| `generate:admin` | Generates admin dashboard controllers and views. | `--react` |
| `generate:admin:controller` | Generates admin dashboard controller. | `--react` |
| `generate:admin:view` | Generates admin dashboard views. | `--react` |
| `generate:auth` | Generates auth controllers and views. | `--react` |
| `generate:auth:controller` | Generates auth controller. | `--react` |
| `generate:auth:views` | Generates auth views. | `--react` |
| `generate:home` | Generates home controllers and views. | `--react` |
| `generate:home:controller` | Generates home controller. | `--react` |
| `generate:home:view` | Generates home index view. | `--react` |
| `generate:profile` | Generates profile controllers and views. | `--react` |
| `generate:profile:controller` | Generates profile controller. | `--react` |
| `generate:profile:views` | Generates profile views.| `--react` |

Each command has a `--react` flag that can be used to deploy the controller or views you want for building a React.js based app.