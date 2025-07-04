<h1 style="font-size: 50px; text-align: center;">Models</h1>

## Table of contents
1. [Overview](#overview)
2. [Model File](#model-file)
    * A. [Blacklist](#blacklist)
    * B. [Whitelist](#whitelist)
    * C. [Usage in Assign Method](#usage)
    * D. [Best Practice](#best-practice)
3. [Description of Functions](#descriptions)
    * A. [addErrorMessage](#add-error-messaage)
    * B. [afterDelete](#after-delete)
    * C. [afterSave](#after-save)
    * D. [beforeDelete](#before-delete)
    * E. [beforeSave](#before-save)
    * F. [onConstruct](#on-construct)
    * G. [runValidation](#run-validation)
    * H. [validationPassed](#validation-passed)
    * I. [validator](#validator)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework supports models for interacting with a database. Whenever you create a new table you will need to create a new model. Let's use the table 'foo' that was created under the Database Operations section. To create a new model run the the following command:

```sh
php console make:model Foo
```

For models responsible for uploads add the `--upload` flag.  More about this feature in the [Uploads](#uploads) section.

After you run the command a new model file will be generated under `app/models`. The make:model command will guess what the name of your table is so be sure to double check the $table variable's value is correct. The output file for the command with modifications tailored to the foo table described above is shown below:

```php
namespace App\Models;
use Core\Model;

/**
 * Implements features of the Foo class.
 */
class Foo extends Model {

    // Fields you don't want saved on form submit
    public const blackList = ['id', 'deleted'];

    // Set to name of database table.
    protected static $_table = 'foo';

    // Soft delete
    protected static $_softDelete = true;
    
    // Fields from your database
    public bar;
    public created_at;
    public id;
    public updated_at;

    public function afterDelete(): void {
        // Implement your function
    }

    public function afterSave(): void {
        // Implement your function
    }

    public function beforeDelete(): void {
        // Implement your function
    }

    public function beforeSave(): void {
        // Implement your function
    }

    /**
     * Performs validation for the ModelName model.
     *
     * @return void
     */
    public function validator(): void {
        // Implement your function
    }
}
```
<br>

## 2. Model File <a id="model-file"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The model class that is generated as a template for creating your models. First thing you need to do is add the fields associated with your database as instance variables. These instance variables need to use the 'public' access identifier. Commonly used functions are automatically generated when you create a new model class. Since all model classes extends the Model class you automatically get access to functions that assist with database operations. Functions you create and those from the Model class are commonly used within action functions within controller classes.

Models also support functions for tasks you want to perform before and after delete and update operations. Finally, the validator function supports server side validation tasks. More information about validation can be found [here](server_side_validation).

This class comes with a `public const blackList` variable that defines which fields should **not** be mass-assigned when processing user input.

You can also optionally define a **whitelist** to explicitly allow only specific fields to be assigned.

<br>

### A. 🔒 Blacklist <a id="blacklist">
Use a blacklist when you want to block sensitive fields from being set by user input (e.g., `id`, `role`, `csrf_token`).

```php
public const blackList = ['id', 'deleted', 'csrf_token'];
```

Then in your controller:

```php
$user->assign($this->request->get(), Users::blackList, Users::BLACKLIST);
```

The framework will automatically ignore any fields listed in the blacklist when assigning values to the model.

<br>

### B. ✅ Whitelist <a id="whitelist">
Alternatively, you can use a whitelist to only allow safe fields:

```php
public const whiteList = ['first_name', 'last_name', 'email'];
```

```php
$user->assign($this->request->get(), Users::whiteList, Users::WHITELIST);
```

This approach is often safer, as it prevents new/accidental fields from being assigned unless explicitly allowed.

<br>

### C. 🔁 Usage in Assign Method <a id="usage">
The assign method accepts the request data and a list of keys to skip or include depending on how your model is configured:

```php
public function assign(array $params, array $list = [], string $assignmentFilter = self::BLACKLIST): self
```

You can toggle the behavior by passing the third parameter using the `WHITELIST` constant if you're using a whitelist.

<br>

### D. ✅ Best Practice <a id="best-practice">
Use whitelisting wherever possible, especially when working with user-submitted data from forms. It minimizes the chance of assigning unintended fields and improves overall security.

<br>

## 3. Description of Functions <a id="descriptions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The following are descriptions for functions not directly related to database operations.  Database related functions can be found [here](database_queries#models)
<br>

### A. addErrorMessage <a id="add-error-message">
Generates error messages that occur during form validation.  You can also set this message whenever certain events occur.  An example is as follows:

```php
$loginModel->addErrorMessage('username', 'There is an error with your username or password.');
```

Here we set an error message associated with a username when there is a failed login attempt.  This function accepts 2 parameters:
1. $field - The form field associated with failed form validation.
2. $message - A message that describes to the user the cause for failed form validation.
<br>

### B. afterDelete <a id="after-delete">
Implement procedures in your model class to perform tasks after deleting a record.
<br>

### C. afterSave <a id="after-save">
Implement procedures in your model class to perform tasks after saving a record.
<br>

### D. beforeDelete <a id="before-delete">
Implement procedures in your model class to perform tasks before deleting a record.
<br>

### E. beforeSave <a id="before-save">
Implement procedures in your model class to perform tasks before saving a record.
<br>

### F. onConstruct <a id="on-construct">
Runs when the object is constructed.  Place statements here that you want every time a particular model in instantiated.  A frequent use case is to set the value for the current logged in user to a variable.
<br>

### G. runValidation <a id="run-validation">
Runs a validator object and sets validates boolean and adds error message if validator fails.  Refer to the [Server Side Validation page](server_side_validation) for more details.
<br>

### H. validationPassed <a id="validation-passed">
Use this function to check if form validation is successful.  An example is shown below:

```php
if($newUser->validationPassed()) {
    if($uploads) {
        ProfileImages::uploadProfileImage($newUser->id, $uploads);
    }
    redirect('auth.login');
}
```

When we create a new user we want to check if form validation has passed before we begin to upload their profile picture to the server.  
<br>

### I. validator <a id="validator">
Use this function to perform form validation.  More about server side validation can be found [here](server_side_validation).