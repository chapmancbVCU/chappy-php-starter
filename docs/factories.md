<h1 style="font-size: 50px; text-align: center;">Database Seeders</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating a Factory Class](#factory-class)
3. [Setting Up the Factory Class](#factory-class-setup)
4. [Image Factories](#image-factories)
5. [Using Factories](#using-factories)
    * A. [Instantiating Factories](#instantiating-factories)
    * B. [States](#states)
    * C. [afterCreating](#after-creating)
    * D. [Sequencing](#sequencing)
    * E. [Function Chaining](#function-chaining)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support the users ability to utilize factories for seeding data and unit testing purposes.  Chappy.php also comes with two factories (`UserFactory` and `ProfileImageFactory`).

Factories also support the following features:
- states
- sequences
- afterCreating

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

## 4. Image Factories <a id="image-factories"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Creating factories for images and uploading them requires a few extra steps.  You will need to use a third-party library called `Smknstd\FakerPicsumImages`.  Let's go over this example for profile images.

```php
<?php
namespace Database\Factories;

use Console\Helpers\Tools;
use Core\DB;
use Core\Models\ProfileImages;
use Core\Lib\Database\Factory;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class ProfileImageFactory extends Factory {
    protected $modelName = ProfileImages::class;
    private $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerPicsumImagesProvider($this->faker));
        $basePath = 'storage' . DS . 'app' . DS . 'private' . DS . 'profile_images' . DS;
        $uploadPath = $basePath . 'user_' . $this->userId . DS;
        Tools::pathExists($uploadPath);

        // Generate the image and get the actual filename from Faker
        $actualFilePath = $this->faker->image($uploadPath, 200, 200, false, null, false, 'jpg');
        
        // Extract only the filename
        $imageFileName = basename($actualFilePath);
        ProfileImages::findAllByUserId($this->userId);
        $sort = ProfileImages::count();
        return [
            'user_id' => $this->userId,
            'sort' => $sort,
            'name' => $imageFileName,
            'url' => $uploadPath . $imageFileName
        ];
    }
}
```

When creating image factories you may want to implement a constructor.  In the example above we provide the id of the user as a parameter for the function.
You will need to import the third-party library, `use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;`, and manage where the file will be uploaded.  If the files do get saved but you are having trouble accessing them make sure the upload path is correct.  

When uploading the image using the `$this->faker->image` function call we set the path, hight, width, and file type.  Next we setup information for the record.  Finally se save the file and produce the appropriate output messages.

Ensure permissions are correct. This is suitable for test environments only.

<br>

## 5. Using Factories <a id="using-factories"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

<br>

### A. Instantiating Factories <a id="instantiating-factories"></a>
A few ways of creating a factory instance is shown below:

```php
$userFactory1 = new UserFactory();
$userFactory1->... chained functions

$userFactory2 = (new UserFactory())->... chained functions

UserFactory::factory()->... chained functions
```

The `factory()` function is used to get an instance of a factory class for elegant syntax with function chaining.

<br>

### B. States <a id="states"></a>
States allows users to override default values generated in the definition function.  These functions are created in your factory class.  

The anonymous functions inside each callback accept the following parameters:

- `$data` - Information from the definition function
- `$attributes` - Array passed as parameter to the `create` function.

Here are two different syntaxes for an example admin function from the `UserFactory`.

**Example 1:**

```php
public function admin(): static {
    return $this->state(function (array $data , array $attributes) {
        return [
            'acl' => json_encode(["Admin"])
        ];
    });
}
```

**Example 2:**

```php
public function admin(): static {
    return $this->state(fn(array $data, array $attributes) => [
        'acl' => json_encode(['Admin']),
    ]);
}
```

You can also invoke other factories using state as follows:
```php
public function withImages(int $count = 2): static {
    return $this->afterCreating(function (Users $user) use ($count) {
        ProfileImageFactory::factory($user->id)->count($count)->create();
    });
}
```

<br>

### C. afterCreating <a id="after-creating"></a>
Use the `afterCreating` feature to perform actions after a record is created with a factory.  Implement the `configure()` function in the parent `Factory` class with any additional task.

```php
protected function configure(): static {
    return $this->afterCreating(function (Users $user) {
        (new ProfileImageFactory($user->id))->count(2)->create();
    });
}
```

Chaining is also supported.

```php
protected function configure(): static
{
    return $this
        ->afterCreating(function (Users $user) {
            (new ProfileImageFactory($user->id))
                ->count(2)
                ->create();
        })
        ->afterCreating(function (Users $user) {
            // Example: assign default role record
            (new UserRoleFactory($user->id))
                ->create();
        });
}
```

Under the hood `afterCreating` is not immutable like `state` and `sequences`.

<br>

### D. Sequencing <a id="sequencing"></a>
You can use sequencing to override default definition values in a specific order.  You will need to chain this function to the tail of the `count` function call.

```php
$factory3 = new UserFactory();
$factory3->count(4)->sequence(
    ['acl' => json_encode(["Admin"])],
    ['acl' => json_encode(["test"])],
)->create();
$factory3->count(2)->sequence(
    ['acl' => json_encode(["foo"])],
    ['acl' => json_encode(["bar"])],
)->create();
```

<br>

### E. Function Chaining <a id="function-chaining"></a>
You can chain together function calls to modify the definition before a record is inserted or change the number of records to be created.

Some examples are shown below:

```php
UserFactory::factory()->admin()->count(1)->create();
UserFactory::factory()->admin()->inactive()->create(['fname' => 'Jane', 'lname' => 'Doe']);
```

More on specific `UserFactory` state function in a later section.