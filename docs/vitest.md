---
layout: default
title: "Vitest"
parent: "Unit Tests"
nav_order: 4
---

# Vitest
## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)

<br>

## 1. Overview <a id="overview"></a>
This guide provides an overview on how to use the console Command Line Interface (CLI) for running Vitest related unit tests.

<br>

## 2. Creating Tests <a id="creating-tests"></a>
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

<br>

## 3. Running Tests <a id="running-tests"></a>
Run all available tests.
```sh
php console react:test
```

**Run Tests By File**

Run all tests in a file that exists within the component, unit, and view test suites..
```sh
php console react:test ${fileName}
```

**Select Particular Test By Line**

Run a specific test in a file.
```sh
php console react:test ${fileName}::${lineNumber}
```

**Running Tests With Vitest**

You can use `npx test run` to bypass the console's `test` command with native support for all of its features.  Their documentation can be found [here](https://vitest.dev/).

**Supported Vitest Flags**

The following flags are supported without running Vitest directly by using `npx vitest run`:

| Flag                       | Description                             |
| -------------------------- | --------------------------------------- |
| `--bail` | Stop after N failure. |
| `--clearCache` | Clears the cache. |
| `--coverage` | Display code coverage summary. |
| `--pass-with-no-tests` | Pass with no tests. |
| `--retry` | Retry failing tests. |
| `--update` | Update snapshots. |

**Run A Test Suite**

Run all test within a particular test suite by adding the `--unit` and/or `--feature` flags.  You can target more than one suite at a time by using more than one flag.

**Run Specific Test File Within A Suite**

You can run all test within a specific test file for an individual suite by specifying the file name and adding the `--unit`, `--component`, or `--view` flags.  You can target more than one suite at a time by using more than one flag.