---
layout: default
title: "api-docs and doctum API Generation"
parent: "Views Introduction"
nav_order: 1
---

# api-docs and doctum API Generation
## Table of contents
1. [Overview](#overview)

<br>

## 1. Overview <a id="overview"></a>
We use doctum to generate documentation based on phpdoc comments for describing classes and functions.  Run the command to view our API documentation:

```sh
php console serve:api --port=<port_number> --host=<hostname_or_ip>
```

Then navigate to `http://<hostname_or_ip>:<port_number>`.  The default is `http://localhost:8001`.