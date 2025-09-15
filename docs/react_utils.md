<h1 style="font-size: 50px; text-align: center;">Console</h1>

## Table of contents
1. [Overview](#overview)
2. [asset](#asset)
3. [documentTitle](#document-title)
4. [route](#route)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework supports the following utilities:
- api - Perform RESTful API operations.  More details in the api page.
- asset - Similar to php asset helper.  Used for rendering images from local storage and S3 buckets.
- documentTitle - Sets title for document.
- route - Similar to php route function.  Pass name of controller/action along with any props.

<br>

## 2. asset <a id="asset"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Used to retrieve assets from the project or S3 bucket.  Unless the `local` parameter is set to `true` it will use S3 bucket specified in `.env` file.

Parameters:
- `path` - Path to the asset
- `local` - Reference path withing project directory structure.  Default value is false.

Example using local:
```jsx
<img className="w-50" src={asset("public/logo.png", true)} alt="Framework Logo" />
```

This retrieves the project logo from the public directory.

Example with S3 bucket:
```jsx
<img src={asset(profileImage.url)}
    className="img-thumbnail mx-auto my-5 d-block w-50 rounded border border-primary shadow-lg"
    loading="lazy"
/>
```

Uses `S3_BUCKET` as host.  By default this variable is set to `APP_DOMAIN`.

<br>

## 3. documentTitle <a id="document-title"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Sets the document title for current page.

Setup:
```jsx
import documentTitle from "@chappy/utils/documentTitle"
```

Usage:
```jsx
function Edit({user, errors, profileImages}) {
    documentTitle(`Edit Details for ${user.username}`);

    return (
        <>
        ...
        </>
    )
}
```

<br>

## 4. route <a id="route"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Redirects a user to a view using dot notation.

Parameters:
- `path` - The path using dot notation (`controller_name.action_name`)
- `params` - Any parameters you want to pass to controller's action as an array

Example:
```jsx
<a href={route('profile.edit', [user.id])} className="btn btn-info btn-sm mx-2 mb-3">
    <i className="fa fa-edit"></i> Edit User Profile
</a>
```