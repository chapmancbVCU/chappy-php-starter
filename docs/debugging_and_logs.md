<h1 style="font-size: 50px; text-align: center;">Debugging and Logs</h1>

## Table of contents
1. [Overview](#overview)
2. [filp/whoops](#whoops)
3. [Log Files](#logs)
4. [Helper Functions](#helpers)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework provides tools for troubleshooting issues with your project.  The first tools to be discussed is the debugging interface provided by the filp/whoops project.  The second is log files.
<br>

## 2. filp/whoops <a id="whoops"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
filp/whoops is a pretty page handler for displaying errors within the browser.  This tool is also included with the Laravel framework.  Thus, inclusion of this tool within this frameworks makes the switch relatively easy.
<br>

## 3. Logs <a id="logs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support two types of log files that can be found at `storage/logs`.  The `app.log` is for logging events when using the framework's front end.  The `cli.log` file logs events associated with Command Line Interface (CLI) console commands.

Since log files can take up a lot of space you can clear them by running the following command:

```sh
php console log:clear
```

This command has 3 flags that can be set.
1. --all - Clears all log files
2. --app - Clears the app.log file
3. --cli - Clears the cli.log file

If the framework has issues writing to the logs files run the following command:
```sh
sudo chown ${user}:${user} -R /storage/logs
```

## 3. Helper Functions <a id="helpers"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
There are 3 functions in the Global Namespace that are used for debugging.
1. cl - A wrapper for the JavaScript console.log function.
2. dd - A wrapper for the VarDumper class' dump function that ends execution of the application where the function is called.
3. dump - A wrapper for the VarDumper class' dump function that continues execution of the application after where the function is called.