<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework has basic support for unit testing.  You can make your own PHPUnit test class by running the following command:

```sh
php console make:Test ${TestName}
```

After running the command a new file is created inside the `tests` directory under project root.  Once you implemented your test you it can be executed by running the following command:
```sh
php console test:run-test ${TestName}
```