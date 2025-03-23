<h1 style="font-size: 50px; text-align: center;">Database Queries</h1>

## Table of contents
1. [Overview](#overview)
2. [DB Class](#db)
    * A. [Create](#create)
    * B. [Read](#read)
    * C. [Update](#update)
    * D. [Delete](#delete)
    * E. [Checking If Value Exists In Column](#value-exists)
    * F. [DB Summary](#db-summary)
3. [Using Models](#models)
    * A. [delete](#model-delete)
    * B. [find](#find)
    * C. [findById](#find-by-id)
    * D. [findFirst](#find-first)
    * E. [findAllByUserId](#find-all-by-user-id)
    * F. [findTotal](#find-total)
    * G. [save](#save)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
There are two ways to perform database queries in this framework.  You can use queries or the functions that comes with your models or base model classes.
<br>

## 2. DB Class <a id="db"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can perform a query within this framework by using the `query` function from the `DB` class.  The query function has 3 parameters:
1. $sql - The database query we will submit to the database.
2. $params - The values for the query.  They are the fiends of the table in our database.  The default value is an empty array.
3. $class - A default value of false, it contains the name of the class we will build based on the name of a model.

An example can be found in the findUserByAcl function from the Users model.  An example is shown below:

```php
/**
 * Retrieves a list of users who are assigned to a particular acl.
 *
 * @param string $acl The ACL we want to use in our query.
 * @return object Users who are assigned to a specific acl.
 */
public static function findUserByAcl($acl) {
    $aclName = '["'.$acl.'"]';
    return self::$_db->query("SELECT * FROM users WHERE acl = ?", [$aclName]);
}
```

All the user has to do is create a classic SQL query as the first parameter.  Since we want to find a list of ACLs we use `aclName` as the parameter that we will bind using the PDO class.  By using the built in `query` function the user does not have to be concerned with the actual binding of values or calling the execute function of the PDO class.
<br>

Here is another example shown below:

```php
use Core\DB;
use Core\Helper;
$db = DB::getInstance();
$sql = "SELECT * FROM contacts";
$contacts = $db->query($sql);
Helper::dd($contacts);
```

Below is the result using the `dd` function:

<div style="text-align: center;">
  <img src="assets/sql-query.png" alt="SQL query example">
  <p style="font-style: italic;">Figure 1 - SQL query example</p>
</div>

As shown in Figure 1 all the information returned from the database is represented as an object.  The `PDOStatement` value has been expanded to show the actual query.  The `_result` section shows all of your contacts.

You can learn more about SQL through this [link](https://www.theodinproject.com/paths/full-stack-javascript/courses/databases) to The Odin Project's Database Course.
<br>

#### A. Create <a id="create">
The **insert** function performs our create operation on our database.  An example is shown below:

```php
use Core\DB;
use Core\Helper;
$db = DB::getInstance();

$fields = [
    'fname' => 'John',
    'lname' => 'Doe',
    'email' => 'example@email.com'
];
$contacts = $db->insert('contacts', $fields);
Helper::dd($contacts);
```

This function accepts two arguments:
1. $table - The name of the table we want to perform the insert operation.
2. $fields - An associative array of key value pairs.  The key is the name of the database field and the value is the value we will set to a particular field.  The default value is an empty array.
<br>

#### B. Read <a id="read">
Users can perform find operations using the DB class with the `find` function using parameters such as conditions, bind, order, limit, and sort.  An example is shown below:

```php
use Core\DB;
use Core\Helper;
$db = DB::getInstance();

$contacts = $db->find('contacts', [
    'conditions' => ["user_id = ?"],
    'bind' => ['1'],
    'limit' => 2,
    'sort' => 'DESC'
]);
Helper::dd($contacts);
```

<div style="text-align: center;">
  <img src="assets/db-find.png" alt="DB Class Find Function">
  <p style="font-style: italic;">Figure 2 - DB Class Find Function</p>
</div>

As shown above in figure 2, we need to first specify the table.  In this case we want to look through our contacts table.  Next, we set our parameters.  Here we use the `user_id` field as the condition, bind to it the `id` of 1, limit the results to the first 2, and sort in descending order.
<br>

This function accepts the following parameters:
1. $table - The name of the table that contains the records we want to retrieve.
2. $params - An associative array that contains key value pair parameters for our query such as conditions, bind, limit, offset, join, order, and sort.  The default value is an empty array.
3. $class A default value of false, it contains the name of the class we will build based on the name of a model.

#### C. Update <a id="update">
You can used the DB class' `update` function to update a record as shown below:

```php
use Core\DB;
use Core\Helper;
$db = DB::getInstance();

$fields = [
    'fname' => 'John',
    'email' => 'example@email.com'
];
$contacts = $db->update('contacts', 3, $fields);
```

This function accepts 3 parameters:
1. $table - The name of the table that contains the record we want to update.
2. $id - The primary key for the record we want to remove from the database table.
3. $fields - An associative array containing key value pairs containing information we want to update.
<br>

#### D. Delete <a id="delete">
The delete function performs delete operations.  This is the simples of all our functions to use as shown below:

```php
$contacts = $db->delete('contacts', 3);
```

It accepts the following arguments:
1. $table - The name of the table that contains the record we want to delete.
2. $id The primary key for the record we want to remove from a database table.
<br>

#### E. Checking If Value Exists In Column <a id="value-exists">
Sometimes you need to check if a value exists in a column in the form of an element in an array.  The `valueExistsInColumn` makes this possible.  Let's check out the manageACLsAction below where we separate ACLs into used and unused.  

```php
public function manageACLsAction(): void {
    $acls = ACL::getACLs();
    $usedAcls = [];
    $unUsedAcls = [];
    foreach($acls as $acl) {
        if($acl->isAssignedToUsers()) {
            array_push($usedAcls, $acl);
        } else {
            array_push($unUsedAcls, $acl);
        }
    }

    $this->view->usedAcls = $usedAcls;
    $this->view->unUsedAcls = $unUsedAcls;
    $this->view->render('admindashboard/manage_acls');
}
```

Here, we want to create two separate arrays.  One for used ACLs and another for unused.  That way we can list them separately.  We do this to prevent administrators from editing used ACLs.  This doesn't prevent administrators from generating queries from making any necessary changes from the MySQL command line or adding such feature through this framework.  Just make sure you test appropriately in development before making you changes in production.
<br>

#### F. Summary  <a id="db-summary">
Many of these functions have their equivalent wrapper functions that will be described in the **Using Models** section.  Here are the descriptions for additional functions:
1. count - Getter function for the private _count variable.
2. findFirst - Returns the first result performed by an SQL query.
3. findTotal - Returns number of records in a table.
4. first - Returns first result in the _result array.
5. lastID - The primary key ID of the last insert operation.
6. results - Returns value of query results.  We usually chain this as a call with another function for our Model classes.  Here is an example we will discuss in later sections: 
    ```php
    $users = Users::findUserByAcl($acl->acl)->results();
    ```
<br>

## 2. Using Models <a id="models"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We will go over using the model class for updating the database.  Each function will be discussed in the following sections.
<br>

#### A. delete  <a id="model-delete">
The delete function is the easiest to use.  Simply call this function with your object as shown below:

```php
$contact->delete()
```

When you implement the **afterDelete** and **beforeDelete** functions in your models they get called when you perform a delete operation.
<br>

#### B. find  <a id="find">
The **find** function is the most commonly used function.  It is a wrapper function for the **DB** class' **find** function.  Throughout this framework this function is used in several of our model classes to perform various tasks.  It accepts one argument called `$params`.  It is an associative array and contains the parameters for your query.

Within the **Users** model this function is used to find all users except the current logged in user.  It's usage is shown below:

```php
public static function findAllUsersExceptCurrent($current_user_id, $params = []) {
    $conditions = [
        'conditions' => 'id != ?',
        'bind' => [(int)$current_user_id]
    ];
    // In case you want to add more conditions
    $conditions = array_merge($conditions, $params);
    return self::find($conditions);
}
```

It accepts two arguments:
1. $current_user_id - The id of the currently logged in user.
2. $params - Used to build conditions for database query.  The default value is an empty array.

Since we want to find all users except current the **conditions** element of the `$conditions` array is set to the value `id != ?`.  Finally, we set `$current_user_id` as the value we want to bind.

Before we move on we will go over a more complex usage.  Within the **ProfileImages** model class we want to find all profile images for a particular user.  This example is shown below:

```php
public static function findByUserId($user_id) {
    return $images = self::find([
        'conditions' => 'user_id = ?',
        'bind' => ['user_id' => $user_id],
        'order' => 'sort'
    ]);
}
```

Besides the conditions we are already familiar with, this one accepts an order parameter whose value is the sort value in the **profile_images** table.
<br>

#### C. findById  <a id="find-by-id">
Use this function if you want to retrieve a record from a database using a record's id field.  An example is shown below:

```php
$user = Users::findById((int)$id);
```

We cast the value is a type int for safety reasons.
<br>

#### D. findAllByUserId  <a id="find-all-by-user-id">
Searches a table where **user_id** is an index value.  An example for using this function is as follows:

```php
$contacts = Contacts::findAllByUserId((int)$this->currentUser->id, ['order'=>'lname, fname']);
```

It accepts two arguments:
1. $user_id - The user ID.
2. $params - Used to build conditions for database query.  The default value is an empty array.  In the example above we want to order by last name and first name.
<br>


#### E. findFirst  <a id="find-first">
This is a wrapper for the **findFirst** function found in the DB class.  It accepts one argument called `$params`.  It is an associative array and contains the parameters for your query.  An example of is usage is shown below:

```php
$user = self::findFirst([
    'conditions' => 'username = ?',
    'bind' => [$this->username]
]);
```

Here we search the users table for a particular username.  Once we find the first record with a match the result is returned.  Here we set `username = ?` as the condition and bind `$this->username` since this is the field we are interested in.

A function that uses this one is the **findByIdAndUserId** function in the Contacts model.  Let's take a closer look.

```php
public static function findByIdAndUserId($contact_id, $user_id, $params = []) {
    $conditions = [
        'conditions' => 'id = ? AND user_id = ?',
        'bind' => [$contact_id, $user_id]
    ];
    $conditions = array_merge($conditions, $params);
    return self::findFirst($conditions);
}
```

We use this function to populate the contact details view by retrieving one contact whose id matches what we want to view along with the user who the contact is associated with.  It accepts three parameters:
1. $contact_id - The ID of the contact whose details we want.
2. $user_id - The ID user associated with this contact.  $user_id is a field that is used as an index for a one-to-many relationship.
3. $params - Used to set additional conditions.  The default value is an empty array.

With this function we use `$contact_id` and `$user_id` to set out binding values.  Next we merge the `$conditions` and `$params` arrays and uses this updated `$conditions` array as the `$parameters` for the **findFirst** function.
<br>

#### F. findTotal  <a id="find-total">
Use this function to find the total number of records based on parameters provided.  Let's look at this example from the the indexAction for the ContactsController.

```php
$pagination = new Pagination($page, 10, Contacts::findTotal([
    'conditions' => 'user_id = ?',
    'bind'       => [$this->currentUser->id]
]));
```

When setting up the Pagination object we use this function to set the value for total items.  In this case, we want to know how many contacts are associated with a particular users.  **user_id** is the index and the current user's id is the value we want to bind.
<br>

#### G. save  <a id="save">
This function is most commonly used in the controllers.  It is a function that performs **insert** and **update** operations.  When you implement the **afterSave** and **beforeSave** functions in your model classes any tasks within those functions are performed when using this function.

There are two ways to use this function.  You can call it standalone as follows:

```php
$user->save()
```

You can also us it as a condition as part of an if statement.

```php
if($user->save()) {
    Router::redirect('admindashboard/details/'.$user->id);
}
```

In the code block above we want to test if the user is saved before we perform a redirect within our controller's action function.