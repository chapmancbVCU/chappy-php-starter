<h1 style="font-size: 50px; text-align: center;">User Guide</h1>

## Table of contents
1. [Overview](#overview)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We use Jekyll to serve our user guide locally and deployment to GitHub pages.  The live link for our user guide can be found [here](https://chapmancbvcu.github.io/chappy-php/)

If you want to update this guide for your own project the files for this guide can be found at the `docs` directory under project root.  The files uses the `.md` extension.  While creating your own guide you can serve the guide locally by running the following command:

```sh
php console serve:docs --port=<port_number> --host=<hostname_or_ip>
```

Once the server is running proceed to the following URL: `http://<hostname_or_ip>:<port_number`.  The default is `http://127.0.0.1:4000/<project_name>/`.