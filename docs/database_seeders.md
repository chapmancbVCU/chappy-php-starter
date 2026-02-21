<h1 style="font-size: 50px; text-align: center;">Database Seeders</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating a Seeder Class](#seeder-class)
3. [Setting Up the Seeder Class](#seeder-class-setup)
4. [Setting Up the DatabaseSeeder Class](#database-seeder)
5. [Running a Database Seeder](#running-seeder)
6. [Seeder Tips and Best Practices](#seeder-tips)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework supports the ability to seed a database with fake data utilizing a package called FakerPHP.  Consult their documentation [here](https://fakerphp.org/) for more information about what they support.  Using this package, along with the native support for Seeder classes you are able to populate tables in your database with test seeder data.  The list of third-party libraries can be found [here](https://fakerphp.org/third-party/).

<br>

## 2. Creating a Seeder Class <a id="seeder-class"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can easily create a new database seeder by running the make:seeder console command.  This command has the following format:

```sh
php console make:seeder ${seederName}
```

The seederName is the name of your model and the table you want to populate with data.  We will use the Contact management system as an example.  Running the following command,

```sh
php console make:seeder Contacts
```

will result in a new file called ContactsTableSeeder being created at `database/seeders/`.  The resulting template file is shown below.

```php
namespace Database\Seeders;

use Core\Lib\Database\Seeder;
use Database\Factories\ContactsFactory;

/**
 * Seeder for contacts table.
 * 
 * @return void
 */
class ContactsTableSeeder extends Seeder {
    /**
     * Runs the database seeder
     *
     * @return void
     */
    public function run(): void {

    }
}
```

This file contains the run function that does the actual work and all of the imports needed to get started.

Each seeder you create must be registered in the `run` function in the `DatabaseSeeder` class.  You can use the call function to run seeders individually or as an array.

**As Strings** 

```php
public function run(): void {
    $this->call(UsersTableSeeder::class);
    $this->call(ProfileImageTableSeeder::class);
}
```

**As an Array**
```php
public function run(): void {
    $this->call([
        UsersTableSeeder::class, 
        ProfileImageTableSeeder::class
    ]);
}
```

<br>

## 3. Setting up The Seeder Class <a id="seeder-class-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We will focus on the following code for a completed run function.

```php
public function run(): void {
    $factory = new ContactsFactory(1);
    $factory->count(5)->create();
    console_info("Seeded contacts table.");
}
```

You will create a new factory and set the parameter in call to the constructor.  Use the `count` function to generate new records.  It takes 1 parameter for the number of records to create.

You can also use the static `factory` function.  The `factory` function accepts the spread parameter so you can supply the `user_id` described in the guide for Factories:

```php
ContactsFactory::factory(1)->count(5)->create();
```

<br>

## 4. Setting Up the DatabaseSeeder Class <a id="database-seeder"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Before we can run the database seeder we need to update the DatabaseSeeder class.  It is found in the same directory as the class that we created above.  You will need to perform the imports and add the function calls in the order you want database seeding to occur.  A properly setup example is shown below:

```php
namespace Database\Seeders;

use Core\Lib\Database\Seeder;
use Database\Seeders\ContactsTableSeeder;

/**
 * Supports ability to call seeder classes.
 */
class DatabaseSeeder extends Seeder {
    /**
     * Call individual seeders in proper order.
     *
     * @return void
     */
    public function run(): void {
        $this->call(ContactsTableSeeder::class);
    }
}
```

Notice that we need to include a use statements for each seeder we want to use.  Within the run function is a call to the `call` function of the Seeder class.  This is where the mechanics of database seeding operations occurs.

<br>

## 5. Running a Database Seeder <a id="running-seeder"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We are finally ready to perform database seeding.  Let's look at the index view of the contacts management system before we start.

<div style="text-align: center;">
  <img src="assets/empty-contacts.png" alt="Contacts' index view with no data">
  <p style="font-style: italic;">Figure 1 - Contacts' index view with no data</p>
</div>

As shown above in Figure 1 we have the index view with no seeders.  Since the Contacts Management System is a testbed for testing development efforts we will want to populate this page with dummy data.  Let's suppose we want to test pagination so we will want to create 20 records.  Run the command below to seed the contacts table.

```sh
php console seed:run
```

After running the command you will see a series of messages describing our task as shown below in Figure 2.

<div style="text-align: center;">
  <img src="assets/seed-run.png" alt="Database seeding console output">
  <p style="font-style: italic;">Figure 2 - Database seeding console output</p>
</div>

Finally, upon inspection of the Contacts' index we will see that the database has been seeded with 20 entries.  Thus, we can go ahead and test the pagination feature.  The example view is shown below in Figure 3.

<div style="text-align: center;">
  <img src="assets/seeded-contacts.png" alt="Contacts' index view after seeder operation">
  <p style="font-style: italic;">Figure 3 - Contacts' index view after seeder operation</p>
</div>

But viewing the Contacts' index view is just one phase of our testing.  We need to click the links for a few of our contacts to make sure the contact information card has the information we expect.  Let's click on the link for our contact, Carmel Leannon, to see their details.

<div style="text-align: center;">
  <img src="assets/contacts-card.png" alt="Carmel Leannon's contact information">
  <p style="font-style: italic;">Figure 4 - Carmel Leannon's contact information</p>
</div>

As shown above in Figure 4, we can see that Carmel's information looks like we would expect.  It contains a valid street address along with a city, state, and zip code that matches USPS standards.  The first name and last name makes sense despite it being completely made up.  Finally, the email and phone number matches a valid format.

⚠️ Note: This command is safe for development/staging only. Avoid using in production unless you're seeding static reference data.

<br>

## 6. Seeder Tips and Best Practices <a id="seeder-tips"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- ✅ Use seeders only in development or staging.
- 🔗 Use foreign key-safe references (`user_id = 1`) that exist.
- 🧠 Use `Faker::unique()` sparingly to avoid memory overuse.
- 🧪 Chain multiple seeders in `DatabaseSeeder` to mirror real-world data order.
- ❌ Avoid using seeders in production unless seeding static or required data (e.g., ACLs, roles).
- 🔄 If using soft deletes, manually set `deleted = 0` where needed.
- 🧰 Keep each table’s seeder separate for modularity and easier maintenance.