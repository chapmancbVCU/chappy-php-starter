<h1 style="font-size: 50px; text-align: center;">PHPUnit</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>



<br>

## 2. Creating Tests <a id="creating-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can create a new test by running the following command:

```bash
php console react:make:test ${testName} --suiteName
```

The following flags for suites are supported:
* `--component` - Creates a new component test under `resources/js/tests/component`
* `--unit` - Creates a new unit test under `resources/js/tests/unit`
* `--view` - Creates a new view test under `resources/js/tests/view`

**Caution On Tests With The Same Name**

* The name of the test files shall be unique across all test suites.  This can cause issues with running tests when filtering by test name and an alert will be presented to the user.
* Enforcement is not so picky if the the file name is the same but the extension is different but you run the risk of running an unintended test.