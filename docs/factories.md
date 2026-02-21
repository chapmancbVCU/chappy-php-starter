<h1 style="font-size: 50px; text-align: center;">Database Seeders</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating a Factory Class](#factory-class)
3. [Setting Up the Factory Class](#factory-class-setup)

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>







<br>

## 2. Creating a Factory Class <a id="factory-class"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Create a new factory class by running the following command:
```bash
php console make:factory <model-name>
```

The new file will be created at `database\factories`.

An example generated factory is shown below:

```php
<?php
namespace Database\Factories;

use App\Models\Contacts;
use Core\Lib\Database\Factory;

class ContactsFactory extends Factory {
    protected $modelName = Contacts::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
        ];
    }
}
```

This file contains the definition that will need to be setup.  

<br>

## 3. Setting up The Factory Class <a id="factory-class-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Below is an example using the UserFactory class:

```php
<?php
namespace Database\Factories;

use App\Models\Contacts;
use Core\Lib\Database\Factory;

class ContactsFactory extends Factory {
    protected $modelName = Contacts::class;
    private $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function definition(): array
    {
        `fname = $this->faker->firstName;`
        `lname` = $this->faker->lastName;
        `email` = $this->faker->unique()->safeEmail;
        `address` = $this->faker->streetAddress;
        `city` = $this->faker->city;
        `state` = $this->faker->stateAbbr;
        `zip` = $this->faker->postcode;
        `home_phone` = $this->faker->phoneNumber;
        `user_id` = $this->userId;
    }
}
```

You will need to set the `$modelName` variable to the name of the model being used.  Next, the array within the definition function needs to be filled out.  We will use an associative array where the keys are all of the fields the model expects when creating a new record.  Each key will be set to a value or call to a faker function.

<br>