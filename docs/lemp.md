<h1 style="font-size: 50px; text-align: center;">LEMP Stack</h1>

## Table of contents
1. [Overview](#overview)
2. [Install System Dependencies](#dependencies)
3. [Install Nginx](#nginx)
4. [Install MySQL/MariaDB](#mysql)
5. [Install PHP](#php)
6. [Configure Nginx and PHP-FPM](#configure-nginx-php)
7. [Install phpMyAdmin](#phpMyAdmin)
8. [Install Composer](#composer)
9. [Install Node.js & NPM](#nodejs)
10. [Project Setup](#project-setup)
11. [Troubleshooting](#troubleshooting)
12. [References](#references)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide walks through setting up a LEMP stack (Linux, Nginx, MySQL/MariaDB, PHP) for deploying PHP applications, including the Chappy.php framework.

**Supported operating systems**

| Family | Versions | Package manager |
|:-------|:---------|:---------------:|
| Ubuntu | 26.04 LTS, 24.04 LTS | `apt` |
| Debian | Debian 13 "trixie" / LMDE 7 "Gigi" | `apt` |
| RHEL | Rocky Linux 10.x, 9.x | `dnf` |

- Commands marked **Ubuntu & Debian** apply to every `apt` system above, and commands marked **Rocky Linux** apply to Rocky 9 and 10 unless noted.
- Rocky Linux 10 requires an x86-64-v3 CPU (roughly Intel Haswell / AMD Excavator or newer). On a VM, set the CPU type to `host` or another v3-capable model.

**Requirements**
- Nginx
- PHP 8.3+ with PHP-FPM
- MySQL or MariaDB
- Composer
- Node.js & NPM
- Git (for cloning the repository)

<br>

## 2. Install System Dependencies <a id="dependencies"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
First, update your system and install essential dependencies:

**Ubuntu & Debian**
```sh
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip ca-certificates net-tools
```

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y epel-release
sudo dnf config-manager --set-enabled crb      # Some EPEL packages depend on CRB
sudo dnf update -y
sudo dnf install -y curl wget git unzip net-tools policycoreutils-python-utils
```
`policycoreutils-python-utils` provides `semanage`, which is used for the SELinux steps in Section 10.
<br>

## 3. Install Nginx <a id="nginx"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. Install Nginx and enable it to start on boot:
**Ubuntu and Debian**
```sh
sudo apt install -y nginx
sudo systemctl enable --now nginx
```

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y nginx
sudo systemctl enable --now nginx
```
<br>

### B. Open the firewall:
**Ubuntu and Debian** (only if `ufw` is enabled)
```sh
sudo ufw allow "Nginx Full"
```

**Rocky Linux (RHEL-based)** (`firewalld` is on by default)
```sh
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```
<br>

### C. Verify Nginx is Running
To confirm that Nginx is running:
```sh
systemctl status nginx
```

The default page for Nginx should be accessible at:
```rust
http://localhost
```
<br>

## 4. Install MySQL/MariaDB <a id="mysql"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Install **one** database server, not both. Chappy.php works with either.

| OS | MariaDB | MySQL |
|:---|:-------:|:-----:|
| Ubuntu 24.04 / 26.04 | ✅ Default repos | ✅ Default repos |
| Debian 13 / LMDE 7 | ✅ Default repos (**recommended**) | ⚠️ Not in Debian repos. Requires the [MySQL APT repository](https://dev.mysql.com/downloads/repo/apt/) |
| Rocky Linux 9 / 10 | ✅ Default repos | ✅ Default repos (AppStream) |

If you don't have a reason to pick MySQL, use **MariaDB**. It's available everywhere in this guide without extra repositories.

### A. Install the Database Server
**MariaDB: Ubuntu & Debian**
```sh
sudo apt install -y mariadb-server
sudo systemctl enable --now mariadb
```

**MariaDB: Rocky Linux**
```sh
sudo dnf install -y mariadb-server
sudo systemctl enable --now mariadb
```

**MySQL: Ubuntu**
```sh
sudo apt install -y mysql-server
sudo systemctl enable --now mysql
```

**MySQL: Rocky Linux**
```sh
sudo dnf install -y mysql-server
sudo systemctl enable --now mysqld
```

Verify the installation:
```sh
mariadb -V    # MariaDB
mysql -V      # MySQL
```
<br>

### B. Secure the Installation
Run the security script for your database:
```sh
sudo mariadb-secure-installation     # MariaDB
sudo mysql_secure_installation       # MySQL
```

The prompts differ slightly between the two. Answer them as follows:

| Prompt | MariaDB | MySQL (Ubuntu) | MySQL (Rocky) |
|:-------|:-------:|:--------------:|:-------------:|
| Enter current password for root | Press **Enter** | — | — |
| VALIDATE PASSWORD COMPONENT | — | `n` (see note) | `n` (see note) |
| Switch to unix_socket authentication | `n` (root already uses it) | — | — |
| Set / change root password | `n` | Skipped (uses `auth_socket`) | **Required**: set one |
| Remove anonymous users | `Y` | `Y` | `Y` |
| Disallow root login remotely | `Y` | `Y` | `Y` |
| Remove test database | `Y` | `Y` | `Y` |
| Reload privilege tables | `Y` | `Y` | `Y` |

**Notes**
- **VALIDATE PASSWORD COMPONENT:** Choosing `y` enforces password strength rules and can cause `ERROR 1819 (HY000)` during the phpMyAdmin install. If you do enable it, level `1` (MEDIUM) is a good balance.
- **Root stays on socket authentication** for MariaDB and for MySQL on Ubuntu. That means `root` logs in with `sudo` and no password, and **cannot** log into phpMyAdmin. This is intentional: you'll create a dedicated application user in the next step and use that for your app and for phpMyAdmin.

When finished you should see a message like:
```rust
All done! If you've completed all of the above steps, your installation should now be secure.
```
<br>

### C. Create the Application Database and User
Log in as root:
```sh
sudo mariadb           # MariaDB (all distros)
sudo mysql             # MySQL on Ubuntu
mysql -u root -p       # MySQL on Rocky Linux
```

Create a database and a user for your app (replace the names and password):
```sql
CREATE DATABASE my_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'my_app_user'@'localhost' IDENTIFIED BY 'your-secure-password';
GRANT ALL PRIVILEGES ON my_app.* TO 'my_app_user'@'localhost';
EXIT;
```

Test the new login:
```sh
mysql -u my_app_user -p my_app
```

These are the values you'll put in `DB_DATABASE`, `DB_USER`, and `DB_PASSWORD` in your `.env` file (Section 10B).
<br>

### D. Optional: Give Root a Password
Only do this if you specifically need password login for `root`. The application user above is the recommended approach.

**MariaDB** (keeps socket login **and** adds a password):
```sql
ALTER USER 'root'@'localhost' IDENTIFIED VIA unix_socket OR mysql_native_password USING PASSWORD('your-secure-password');
```

**MySQL on Ubuntu:**
```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH caching_sha2_password BY 'your-secure-password';
```

> ⚠️ Older guides use `IDENTIFIED WITH mysql_native_password`. That plugin is disabled by default in MySQL 8.4 and removed in MySQL 9, so use `caching_sha2_password` with MySQL.

Verify:
```sql
SELECT user, host, plugin FROM mysql.user;
```
<br>

## 5. Install PHP <a id="php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php requires PHP 8.3 or newer. On Ubuntu and Debian, the PHP in the default repositories already meets that, so no third-party repository is needed. Rocky Linux uses the Remi repository.

| OS | PHP version installed |
|:---|:---------------------:|
| Ubuntu 24.04 | 8.3 |
| Ubuntu 26.04 | 8.5 |
| Debian 13 / LMDE 7 | 8.4 |
| Rocky Linux 9 / 10 (Remi) | 8.4 |

### A. Ubuntu & Debian
```sh
sudo apt install -y php-fpm php-cli php-mbstring php-xml php-curl php-zip php-mysql php-sqlite3 php-bcmath php-intl php-gd sqlite3
```

These version-independent package names install whatever PHP version your release ships, so the same command works on every Ubuntu and Debian version in this guide.

> ⚠️ Don't install the bare `php` package on a LEMP server. It pulls in Apache (`libapache2-mod-php`) by default, which will fight Nginx for port 80. Installing `php-fpm` as shown avoids this.

The PHP-FPM service name includes the version. Find yours and make sure it's running:
```sh
php -v                                   # e.g. PHP 8.4.x → service is php8.4-fpm
sudo systemctl enable --now php<version>-fpm
```
<br>

### B. Rocky Linux (RHEL-based)
```sh
sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-$(rpm -E %rhel).rpm
sudo dnf module reset php -y
sudo dnf module enable php:remi-8.4 -y      # Enable PHP 8.4 from Remi
sudo dnf install -y php-fpm php-cli php-mbstring php-xml php-curl php-zip php-mysqlnd php-pdo php-bcmath php-gd php-opcache php-intl php-soap
sudo systemctl enable --now php-fpm
```

`$(rpm -E %rhel)` expands to `9` or `10`, so the same command works on both Rocky versions.

> On Rocky, PHP-FPM runs as the `apache` user by default, even without Apache installed, and already grants Nginx access to its socket. This guide keeps that default, so the PHP-writable `storage` directory uses the `apache` group (Section 10E).

<br>

### C. Verify installation:
```sh
php -v
```
<br>

## 6. Configure Nginx and PHP-FPM <a id="configure-nginx-php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. Create Temporary Location
We will temporarily create the location for our app so we can test PHP. It gets replaced by the real project in Section 10:
```sh
sudo mkdir -p /var/www/my-app
```
<br>

### B. Set proper permissions:

**Ubuntu and Debian**
```sh
sudo chown -R your-username:www-data /var/www/my-app
sudo chmod -R 755 /var/www/my-app
```

**Rocky Linux (RHEL-based)**
```sh
sudo chown -R your-username:nginx /var/www/my-app
sudo chmod -R 755 /var/www/my-app
```
<br>

### C. Create a new configuration file:

**Ubuntu & Debian**
```sh
sudo vi /etc/nginx/sites-available/my-app
```

Paste the following content (replace my-app.local and my_ip_address with information relevant for your case):
```rust
server {
    listen 80;
    server_name my-app.local my_ip_address;
    root /var/www/my-app;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    # Must be at least as large as PHP's post_max_size (Section 6E)
    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

`/run/php/php-fpm.sock` is a version-independent link to the installed PHP-FPM socket, so this file doesn't need editing when PHP is upgraded. If Nginx returns 502 Bad Gateway, see Troubleshooting B.

Enable the new site and disable default on Ubuntu/Debian:
```sh
sudo ln -s /etc/nginx/sites-available/my-app /etc/nginx/sites-enabled/
sudo unlink /etc/nginx/sites-enabled/default
```
<br>

**Rocky Linux (RHEL-based)**
```sh
sudo vi /etc/nginx/conf.d/my-app.conf
```

Paste the following content (replace my-app.local and my_ip_address with information relevant for your case):
```rust
server {
    listen 80;
    server_name my-app.local my_ip_address;
    root /var/www/my-app;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    # Must be at least as large as PHP's post_max_size (Section 6E)
    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_pass unix:/run/php-fpm/www.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Allow Nginx/PHP to connect to the database. Because `DB_HOST=127.0.0.1` connects over TCP, SELinux must allow it:
```sh
sudo setsebool -P httpd_can_network_connect_db 1
```

Only if your app makes outbound connections (external APIs, SMTP, etc.):
```sh
sudo setsebool -P httpd_can_network_connect 1
sudo setsebool -P httpd_can_sendmail 1
```
<br>

### D. Test PHP
Then test the configuration and reload Nginx:
```sh
sudo nginx -t
sudo systemctl reload nginx
```

Create a test file:
```sh
echo "<?php phpinfo(); ?>" | sudo tee /var/www/my-app/info.php
```

Open in a browser:
```rust
http://localhost/info.php
```

Remove the temporary directory after testing (the real project is moved here in Section 10):
```sh
sudo rm -rf /var/www/my-app/
```
<br>

### E. Configure Upload Size (for Profile Image Support):

**Ubuntu and Debian**

The path includes your PHP version (for example `8.3`, `8.4`, or `8.5`). Check it with `php -v`, then edit:
```sh
sudo vi /etc/php/<version>/fpm/php.ini
```

**Rocky Linux (RHEL-based)**
```sh
sudo vi /etc/php.ini
```

Then modify the following settings:

| Setting | What it controls | Safe recommended value |
|:-------:|-------------|-------------|
| `upload_max_filesize` | Max size of a single uploaded file | `5M` (or `10M` if high-res image uploads) |
| `post_max_size` | Max size of total POST body (form fields + files) | `8M` (or `15M` if `upload_max_filesize` is `10M`) |
| `max_execution_time` | Max script run time (seconds) | `30` to `60` |
| `memory_limit` | Max memory a script can use | `128M` (or `256M` for image-heavy apps) |

These values should be set depending on what type of files are being uploaded. Files such as videos should be much higher. `post_max_size` should be greater than `upload_max_filesize`. Otherwise, you will get a corrupted token error instead.

**Nginx has its own limit.** `client_max_body_size` in your server block (Section 6C) must be at least as large as `post_max_size`, or Nginx will reject the upload with `413 Request Entity Too Large` before PHP ever sees it. Its default is only `1M`.

<br>

Then restart PHP-FPM:

**Ubuntu and Debian**
```sh
sudo systemctl restart php<version>-fpm
```

**Rocky Linux (RHEL-based)**
```sh
sudo systemctl restart php-fpm
```
<br>
<br>

## 7. Install phpMyAdmin <a id="phpMyAdmin"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
phpMyAdmin provides a web interface to manage MySQL or MariaDB databases.
### A. Install phpMyAdmin
**Ubuntu & Debian**
```sh
sudo apt install -y --no-install-recommends phpmyadmin
```

`--no-install-recommends` stops apt from pulling in Apache alongside phpMyAdmin.

When prompted:
- Do not select a web server (Nginx is not listed). Press **Tab** then **Enter** to continue with nothing selected.
- Choose Yes to configure dbconfig-common and create the phpMyAdmin database.
- Set a phpMyAdmin password or leave it blank to auto-generate.
- If you get `ERROR 1819 (HY000)`, see the Troubleshooting section.

<br>

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y phpMyAdmin
# If the package isn't found:
sudo dnf --enablerepo=remi install -y phpMyAdmin
```
<br>

### B. Configure Nginx to Serve phpMyAdmin
**Ubuntu & Debian**

Symlink phpMyAdmin into your Nginx-accessible root path:
```sh
sudo ln -s /usr/share/phpmyadmin /var/www/phpmyadmin
```

Then edit your Nginx config:
```sh
sudo vi /etc/nginx/sites-available/my-app
```

Add the following inside the `server {}` block:
```rust
location /phpmyadmin {
    root /var/www;
    index index.php index.html index.htm;

    location ~ ^/phpmyadmin/(.+\.php)$ {
        try_files $uri =404;
        root /var/www;
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))$ {
        root /var/www;
    }
}
```
- **Note:** If you get a 502 Bad Gateway error, see Troubleshooting B.
- If you're using a custom root path like /var/www/my-app, adjust root accordingly.

<br>

**Rocky Linux (RHEL-based)**

Symlink phpMyAdmin into your Nginx-accessible root path:
```sh
sudo ln -s /usr/share/phpMyAdmin /var/www/phpmyadmin
```

Then edit your Nginx config:
```sh
sudo vi /etc/nginx/conf.d/my-app.conf
```

Add the following inside the `server {}` block:
```rust
location /phpmyadmin {
    root /var/www;
    index index.php index.html index.htm;

    location ~ ^/phpmyadmin/(.+\.php)$ {
        try_files $uri =404;
        root /var/www;
        fastcgi_pass unix:/run/php-fpm/www.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))$ {
        root /var/www;
    }
}
```
<br>

Reload Nginx to apply the config:
```sh
sudo nginx -t
sudo systemctl reload nginx
```
<br>

### C. Verify phpMyAdmin Installation
Open your browser and visit:
```rust
http://localhost/phpmyadmin
```

Log in with the **application user** you created in Section 4C (for example `my_app_user`). `root` cannot log in through phpMyAdmin while it uses socket authentication. That's expected.

<br>

## 8. Install Composer <a id="composer"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Composer is required to manage PHP dependencies.
### A. Download and Install Composer
```sh
cd ~/
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```
<br>

### B. Verify Installation
```sh
composer -V
```
<br>

## 9. Install Node.js & NPM <a id="nodejs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Use NodeSource to install the latest LTS Node.js version.
### A. Add Node.js Repository
**Ubuntu and Debian**
```sh
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo bash -
```

**Rocky Linux (RHEL-based)**
```sh
curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
```
<br>

### B. Install Node.js & NPM
**Ubuntu and Debian**
```sh
sudo apt install -y nodejs
```

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y nodejs
```
<br>

### C. Verify Installation
```sh
node -v
npm -v
```
<br>

## 10. Project Setup <a id="project-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. Navigate to your user's home directory and install dependencies:
Create your project and replace my-app with the name of your project. Then move to location described below:
```sh
cd ~/
composer create-project chappy-php/chappy-php my-app
sudo mv my-app/ /var/www/
cd /var/www/my-app
```

<br>

### B. Project Configuration

Open your preferred IDE (We use VSCode) and edit the `.env` file:
- Set `APP_DOMAIN` to `/`.
- For **security** purposes ensure `DB_LOG_PARAMS` is set to `none` instead of `full`.
- Set `LOGGING` environmental variable as appropriate for your use. In production we recommend `info` to prevent excessive logging.
- Update the database section with the database and user from Section 4C:
```php
# Set to mysql or mariadb
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_app
DB_USER=my_app_user
DB_PASSWORD=your-secure-password
```

<br>

### C. Update /etc/hosts (For Custom Domain)
If you want to access your site using http://my-app.local, you can edit your /etc/hosts file:
```sh
sudo vi /etc/hosts
```

Example configuration:
```rust
127.0.0.1       localhost my-app.local
127.0.1.1       ubuntu-vm
your_ip_addr    my-app.local
```

Now, http://my-app.local will work as expected.

<br>

### D. Enable the Site:
Restart Nginx after modifying the server block or /etc/hosts:
```sh
sudo nginx -t
sudo systemctl restart nginx
```

<br>

**Rocky Linux (RHEL-based)**
#### 1. Fix SELinux Contexts
Files moved from your home directory keep their old SELinux label, so Nginx and PHP-FPM can't read them. Reset the labels:
```sh
sudo restorecon -Rv /var/www/my-app
```

#### 2. Make Storage Writable
Mark `storage` as writable by PHP. This is persistent and survives reboots and relabels:
```sh
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/my-app/storage(/.*)?"
sudo restorecon -Rv /var/www/my-app/storage
```
📁 If you're using other writable paths, repeat these steps for those as well.

Now restart Nginx:
```sh
sudo systemctl restart nginx
```
<br>

### E. Final Steps
Set permissions and ownership. The `storage` directory must be writable by the PHP-FPM user for logs and uploads.

**Ubuntu & Debian** (PHP-FPM runs as `www-data`)
```sh
sudo chown -R your-username:www-data /var/www/my-app
```

**Rocky Linux (RHEL-based)** (PHP-FPM runs as `apache`)
```sh
sudo chown -R your-username:apache /var/www/my-app
```

**All distros**
```sh
sudo find /var/www/my-app -type d -exec chmod 755 {} \;
sudo find /var/www/my-app -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/my-app/storage
```
<br>

Create JavaScript bundle if necessary and run migrations:
```sh
cd /var/www/my-app
npm run build
php console migrate
```

Your project should now be accessible at:
```rust
http://<your_ip_address>
```
<br>

## 11. Troubleshooting <a id="troubleshooting"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. Database Issues
- **ERROR 1819 (HY000) during phpMyAdmin installation**: Your password does not meet MySQL's VALIDATE PASSWORD policy. Use a stronger password, or remove the component:
```sql
UNINSTALL COMPONENT 'file://component_validate_password';
```
- **`Access denied for user 'root'@'localhost'` in phpMyAdmin**: Expected when root uses socket authentication. Log in with your application user (Section 4C), or see Section 4D.
- **`Plugin 'mysql_native_password' is not loaded`**: You're on MySQL 8.4+. Use `caching_sha2_password` instead (Section 4D).
- **App can't connect to the database on Rocky Linux**: Run `sudo setsebool -P httpd_can_network_connect_db 1` (Section 6C).
<br>

### B. 502 Bad Gateway
Nginx can't reach PHP-FPM. Check that PHP-FPM is running and that the socket in your config exists:

**Ubuntu & Debian**
```sh
systemctl status php<version>-fpm
ls -l /run/php/
```
If `php-fpm.sock` isn't listed, use the versioned socket shown (for example `php8.4-fpm.sock`) in your `fastcgi_pass` lines.

**Rocky Linux**
```sh
systemctl status php-fpm
ls -l /run/php-fpm/
```

Then check the Nginx error log:
```sh
sudo tail -n 50 /var/log/nginx/error.log
```
<br>

### C. 413 Request Entity Too Large
Uploads are larger than Nginx allows. Increase `client_max_body_size` in your server block (Section 6C) and reload Nginx.
<br>

### D. Port 80 Already in Use / Nginx Won't Start
Apache may have been installed as a dependency (usually by installing the bare `php` package, or phpMyAdmin without `--no-install-recommends`). Remove it:
```sh
sudo apt purge -y apache2 libapache2-mod-php*     # Ubuntu & Debian
sudo systemctl restart nginx
```
<br>

### E. Finding SELinux Denials (Rocky Linux)
If you get 403 errors, blank pages, or write failures that permissions don't explain, check for SELinux denials:
```sh
sudo ausearch -m avc -ts recent
```
<br>

### F. Rocky Linux 10 Won't Boot or Install
Rocky Linux 10 requires an x86-64-v3 CPU. On older hardware, use Rocky Linux 9. On a VM, change the CPU type to `host` (or another v3-capable model).
<br>

## 12. References <a id="references"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A. [How To Install Linux, Nginx, MySQL, PHP (LEMP stack) on Ubuntu](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu)

B. [How To Install and Configure Laravel with Nginx on Ubuntu (LEMP)](https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-laravel-with-nginx-on-ubuntu-22-04)

C. [How To Install and Secure phpMyAdmin with Nginx on an Ubuntu Server](https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-with-nginx-on-an-ubuntu-20-04-server)

D. [Remi's RPM Repository - Configuration Wizard](https://rpms.remirepo.net/wizard/)

E. [Rocky Linux Releases](https://docs.rockylinux.org/releases/)