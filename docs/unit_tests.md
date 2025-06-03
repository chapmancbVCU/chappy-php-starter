<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework has support for unit testing.  

<br>

## 2. Creating Tests <a id="creating-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can make your own PHPUnit test class by running the following command:

```sh
php console make:test ${testName}
```

By default the `make:test` places the file under the unit test suite.  To create a feature test run:

```sh
php console make:test ${testName} --feature
```

<br>


## 3. Running Tests <a id="running-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
After running the command a new file is created inside the `tests` directory under project root.  Once you implemented your test you it can be executed by running the following command:
```sh
php console test ${testName}
```