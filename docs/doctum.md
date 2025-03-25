<h1 style="font-size: 50px; text-align: center;">api-docs and doctum API Generation</h1>

## Table of contents
1. [Overview](#overview)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We use doctum to generate documentation based on phpdoc comments for describing classes and functions.  Run the command to view our API documentation:

```sh
php console serve:api --port=<port_number> --host=<hostname_or_ip>
```

Then navigate to `http://<hostname_or_ip>:<port_number>`.  The default is `http://localhost:8001`.