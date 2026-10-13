<h1 style="font-size: 50px; text-align: center;">LAMP Stack</h1>

## Table of contents
1. [Overview](#overview)
2. [Install System Dependencies](#dependencies)
3. [Install Apache](#apache)
4. [Install MySQL/MariaDB](#mysql)
5. [Install PHP](#php)
6. [Configure Apache and PHP](#configure-apache-php)
7. [Install phpMyAdmin](#phpMyAdmin)
8. [Install Composer](#composer)
9. [Install Node.js & NPM](#nodejs)
10. [Project Setup](#project-setup)
11. [Troubleshooting](#troubleshooting)
12. [References](#references)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide walks through setting up a LAMP stack (Linux, Apache, MySQL/MariaDB, PHP) for deploying PHP applications, including the Chappy.php framework.

**Supported operating systems**

| Family | Versions | Package manager |
|:-------|:---------|:---------------:|
| Ubuntu | 26.04 LTS, 24.04 LTS | `apt` |
| Debian | Debian 13 "trixie" / LMDE 7 "Gigi" | `apt` |
| RHEL | Rocky Linux 10.x, 9.x | `dnf` |

- Commands marked **Ubuntu & Debian** apply to every `apt` system above, and commands marked **Rocky Linux** apply to Rocky 9 and 10 unless noted.
- Rocky Linux 10 requires an x86-64-v3 CPU (roughly Intel Haswell / AMD Excavator or newer). On a VM, set the CPU type to `host` or another v3-capable model.

**Requirements**
- Apache 2.4
- PHP 8.3+
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
<br>

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y epel-release
sudo dnf config-manager --set-enabled crb      # Some EPEL packages depend on CRB
sudo dnf update -y
sudo dnf install -y curl wget git unzip net-tools policycoreutils-python-utils
```
`policycoreutils-python-utils` provides `semanage`, which is used for the SELinux steps in Section 10.
<br>

## 3. Install Apache <a id="apache"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. Install Apache and enable it to start on boot:
**Ubuntu and Debian**
```sh
sudo apt install -y apache2
sudo systemctl enable --now apache2
```
<br>

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y httpd
sudo systemctl enable --now httpd
```
<br>

### B. Open the firewall:
**Ubuntu and Debian** (only if `ufw` is enabled)
```sh
sudo ufw allow "Apache Full"
```

**Rocky Linux (RHEL-based)** (`firewalld` is on by default)
```sh
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```
<br>

### C. Verify Apache is running:
```sh
systemctl status apache2   # Ubuntu & Debian
systemctl status httpd     # Rocky Linux
```
<br>

### D. Apache should now be accessible at:
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

These are the values you'll put in `DB_DATABASE`, `DB_USER`, and `DB_PASSWORD` in your `.env` file (Section 10C).
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
sudo apt install -y php php-cli php-mbstring php-xml php-curl php-zip php-mysql php-sqlite3 php-bcmath php-intl php-gd libapache2-mod-php sqlite3
```

These version-independent package names install whatever PHP version your release ships, so the same command works on every Ubuntu and Debian version in this guide.

<br>

### B. Rocky Linux (RHEL-based)
```sh
sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-$(rpm -E %rhel).rpm
sudo dnf module reset php -y
sudo dnf module enable php:remi-8.4 -y      # Enables PHP 8.4 from Remi repo
sudo dnf install -y php php-cli php-fpm php-mbstring php-xml php-curl php-zip php-mysqlnd php-pdo php-bcmath php-gd php-opcache php-intl php-soap
sudo systemctl enable --now php-fpm
```

`$(rpm -E %rhel)` expands to `9` or `10`, so the same command works on both Rocky versions. On Rocky, Apache runs PHP through **PHP-FPM** rather than `mod_php`, which matters when you change `php.ini` (Section 6).

<br>

### C. Verify installation:
```sh
php -v
```
<br>

## 6. Configure Apache and PHP <a id="configure-apache-php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Restart Apache to apply PHP settings:
```sh
sudo systemctl restart apache2   # Ubuntu & Debian
sudo systemctl restart httpd     # Rocky Linux
```

Test PHP:
- Create a test file:
```sh
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php
```

- Open in a browser:
```rust
http://localhost/info.php
```

- Remove the file after testing:
```sh
sudo rm /var/www/html/info.php
```
<br>

Configure upload size for profile image upload support. Edit the file:

**Ubuntu & Debian**

The path includes your PHP version (for example `8.3`, `8.4`, or `8.5`). Check it with `php -v`, then edit:
```sh
sudo vi /etc/php/<version>/apache2/php.ini
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

Apply the changes:
```sh
sudo systemctl restart apache2                  # Ubuntu & Debian
sudo systemctl restart php-fpm httpd            # Rocky Linux (PHP-FPM must be restarted too)
```

<br>

## 7. Install phpMyAdmin <a id="phpMyAdmin"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
phpMyAdmin provides a web interface to manage MySQL or MariaDB databases.
### A. Install phpMyAdmin
**Ubuntu & Debian**
```sh
sudo apt install -y phpmyadmin
```

During installation:
- Select **apache2** when prompted (press Space to select it, then Enter).
- Choose **Yes** to configure the database with dbconfig-common.
- Set a phpMyAdmin password (or leave blank to generate one).
- If you get `ERROR 1819 (HY000)`, see the Troubleshooting section.

The package enables its own Apache config, so no symlink is needed. Restart Apache:
```sh
sudo systemctl restart apache2
```
<br>

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y phpMyAdmin
# If the package isn't found:
sudo dnf --enablerepo=remi install -y phpMyAdmin
sudo systemctl restart httpd
```

On Rocky, phpMyAdmin is only reachable from the server itself by default. To allow access from another machine, edit `/etc/httpd/conf.d/phpMyAdmin.conf` and add `Require ip your_ip_address` next to `Require local`, then restart `httpd`.
<br>

### B. Verify phpMyAdmin Installation
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
### A. Navigate to your user's home directory, install dependencies, then move to final location:
Create your project and replace my-app with the name of your project.
```sh
cd ~/
composer create-project chappy-php/chappy-php my-app
sudo mv my-app /var/www/html
cd /var/www/html/my-app
```
<br>

### B. Set proper permissions:
**Ubuntu and Debian**
```sh
sudo chown -R your-username:www-data /var/www/html/my-app
```

**Rocky Linux (RHEL-based)**
```sh
sudo chown -R your-username:apache /var/www/html/my-app
```

**All distros**
```sh
sudo find /var/www/html/my-app -type d -exec chmod 755 {} \;
sudo find /var/www/html/my-app -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/html/my-app/storage
```
The `storage` directory needs group write access for logs and uploads.
<br>

### C. Project Configuration
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

### D. Apache Virtual Host Configuration
Run the following command to create a new Apache configuration file:

**Ubuntu and Debian**
```sh
sudo vi /etc/apache2/sites-available/my-app.conf
```
Paste the following content into the file (adjust ServerName to your actual IP or domain):

```rust
<VirtualHost *:80>
    ServerName localhost
    ServerAlias your_ip_address my-app.local
    DocumentRoot /var/www/html/my-app

    <Directory /var/www/html/my-app>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Save and exit (ESC then :wq).
<br>

**Rocky Linux (RHEL-based)**

For `.htaccess` files to work correctly on Rocky Linux, Apache needs `AllowOverride All`. `Options FollowSymLinks` lets rewrite rules work.
```sh
sudo vi /etc/httpd/conf.d/my-app.conf
```

Paste the following content into the file (replace my-app.local and your_ip_address with information relevant for your case):

```rust
<VirtualHost *:80>
    ServerName localhost
    ServerAlias your_ip_address my-app.local
    DocumentRoot /var/www/html/my-app

    <Directory /var/www/html/my-app>
        AllowOverride All
        Require all granted
        Options FollowSymLinks
    </Directory>

    ErrorLog /var/log/httpd/my-app-error.log
    CustomLog /var/log/httpd/my-app-access.log combined
</VirtualHost>
```

Save and exit (ESC then :wq).
<br>

**Update /etc/hosts (For Custom Domain)**

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
<br>

### E. Enable the Site:
**Ubuntu and Debian**
```sh
# Enable mod_rewrite first
sudo a2enmod rewrite

# Disable the default site so it doesn't catch requests by IP
sudo a2dissite 000-default.conf

# Then enable the VirtualHost
sudo a2ensite my-app.conf
sudo apache2ctl configtest
sudo systemctl restart apache2
```
<br>

**Rocky Linux (RHEL-based)**

`mod_rewrite` is already enabled on Rocky Linux (see Troubleshooting D if rewrites aren't working).

#### 1. Fix SELinux Contexts
Files moved from your home directory keep their old SELinux label, so Apache can't read them. Reset the labels:
```sh
sudo restorecon -Rv /var/www/html/my-app
```

#### 2. Make Storage Writable
Mark `storage` as writable by Apache. This is persistent and survives reboots and relabels:
```sh
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/html/my-app/storage(/.*)?"
sudo restorecon -Rv /var/www/html/my-app/storage
```
📁 If you're using other writable paths, repeat these steps for those as well.

#### 3. Allow Apache to Connect to the Database
Because `DB_HOST=127.0.0.1` connects over TCP, SELinux must allow Apache to reach the database port:
```sh
sudo setsebool -P httpd_can_network_connect_db 1
```

Only if your app makes outbound connections (external APIs, SMTP, etc.):
```sh
sudo setsebool -P httpd_can_network_connect 1
sudo setsebool -P httpd_can_sendmail 1
```

Now check the config and restart Apache:
```sh
sudo apachectl configtest
sudo systemctl restart httpd
```
<br>

### F. Final Steps
Run migrations:
```sh
cd /var/www/html/my-app
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
- **App can't connect to the database on Rocky Linux**: Run `sudo setsebool -P httpd_can_network_connect_db 1` (Section 10E).
<br>

### B. phpMyAdmin 403 Forbidden (Rocky Linux)
phpMyAdmin on Rocky only allows local access by default. Edit `/etc/httpd/conf.d/phpMyAdmin.conf`, add `Require ip your_ip_address` alongside `Require local`, and restart `httpd`.
<br>

### C. PHP Settings Not Taking Effect (Rocky Linux)
Rocky runs PHP through PHP-FPM, so restarting Apache alone doesn't reload `php.ini`:
```sh
sudo systemctl restart php-fpm
```
<br>

### D. mod_rewrite for Rocky Linux (RHEL)
`mod_rewrite` is loaded by default from `/etc/httpd/conf.modules.d/00-base.conf`. Confirm it's active:
```sh
httpd -M | grep rewrite
```

If nothing is returned, make sure this line exists and isn't commented out in `/etc/httpd/conf.modules.d/00-base.conf`:
```sh
LoadModule rewrite_module modules/mod_rewrite.so
```

If the module is loaded but rewrites still fail, check that your VirtualHost has `AllowOverride All`. Then restart Apache:
```sh
sudo systemctl restart httpd
```
<br>

### E. Finding SELinux Denials (Rocky Linux)
If you get 403 errors or write failures that permissions don't explain, check for SELinux denials:
```sh
sudo ausearch -m avc -ts recent
```
<br>

### F. Rocky Linux 10 Won't Boot or Install
Rocky Linux 10 requires an x86-64-v3 CPU. On older hardware, use Rocky Linux 9. On a VM, change the CPU type to `host` (or another v3-capable model).
<br>

## 12. References <a id="references"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A. [How To Install Linux, Apache, MySQL, PHP (LAMP) Stack on Ubuntu - Digital Ocean](https://www.digitalocean.com/community/tutorials/how-to-install-lamp-stack-on-ubuntu)

B. [How To Install and Secure phpMyAdmin on Ubuntu - Digital Ocean](https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-ubuntu)

C. [How to Deploy a Laravel App Using Apache and MySQL](https://adeyomoladev.medium.com/how-to-deploy-a-laravel-app-using-apache-and-mysql-4910a07f9a0c)

D. [Remi's RPM Repository - Configuration Wizard](https://rpms.remirepo.net/wizard/)

E. [Rocky Linux Releases](https://docs.rockylinux.org/releases/)