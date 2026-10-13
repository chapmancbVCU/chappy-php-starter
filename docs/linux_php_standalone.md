<h1 style="font-size: 50px; text-align: center;">Linux Standalone PHP</h1>

## Table of contents
1. [Overview](#overview)
2. [Install System Dependencies](#dependencies)
3. [Install PHP](#php)
4. [Install Composer](#composer)
5. [Install Node.js & NPM](#nodejs)
6. [Project Setup](#project-setup)
7. [Troubleshooting](#troubleshooting)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide walks through setting up the Chappy.php framework for local development without requiring XAMPP, Nginx, or Apache. The framework is served using PHP's built-in development server (`php console serve`).

> ⚠️ PHP's built-in server is for development only. For production, use the LAMP or LEMP guide.

**Supported operating systems**

| Family | Versions | Package manager |
|:-------|:---------|:---------------:|
| Ubuntu | 26.04 LTS, 24.04 LTS | `apt` |
| Debian | Debian 13 "trixie" / LMDE 7 "Gigi" | `apt` |
| RHEL | Rocky Linux 10.x, 9.x | `dnf` |

- Commands marked **Ubuntu & Debian** apply to every `apt` system above, and commands marked **Rocky Linux** apply to Rocky 9 and 10 unless noted.
- Rocky Linux 10 requires an x86-64-v3 CPU (roughly Intel Haswell / AMD Excavator or newer). On a VM, set the CPU type to `host` or another v3-capable model.

**Requirements**
- PHP 8.3+
- Composer
- Node.js & NPM
- Git (for cloning the repository)

<br>

## 2. Install System Dependencies <a id="dependencies"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
First, update your system and install essential dependencies:

**Ubuntu & Debian**
```sh
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip ca-certificates
```

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y epel-release
sudo dnf config-manager --set-enabled crb      # Some EPEL packages depend on CRB
sudo dnf update -y
sudo dnf install -y curl wget git unzip
```
<br>

## 3. Install PHP <a id="php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php requires PHP 8.3 or newer. On Ubuntu and Debian, the PHP in the default repositories already meets that, so no third-party repository is needed. Rocky Linux uses the Remi repository.

| OS | PHP version installed |
|:---|:---------------------:|
| Ubuntu 24.04 | 8.3 |
| Ubuntu 26.04 | 8.5 |
| Debian 13 / LMDE 7 | 8.4 |
| Rocky Linux 9 / 10 (Remi) | 8.4 |

Only the PHP **CLI** is installed. The built-in server runs from the command line, so no web server or PHP-FPM is needed.

### A. Ubuntu & Debian
```sh
sudo apt install -y php-cli php-mbstring php-xml php-curl php-zip php-sqlite3 php-bcmath php-intl php-gd sqlite3
```

These version-independent package names install whatever PHP version your release ships, so the same command works on every Ubuntu and Debian version in this guide.

> ⚠️ Install `php-cli`, not the bare `php` package. On Ubuntu and Debian, `php` pulls in Apache by default.

If you'll connect to MySQL or MariaDB instead of SQLite, also install:
```sh
sudo apt install -y php-mysql
```
<br>

### B. Rocky Linux (RHEL-based)
```sh
sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-$(rpm -E %rhel).rpm
sudo dnf module reset php -y
sudo dnf module enable php:remi-8.4 -y      # Enables PHP 8.4 from Remi repo
sudo dnf install -y php-cli php-mbstring php-xml php-curl php-zip php-pdo php-bcmath php-intl php-gd sqlite
```

- `$(rpm -E %rhel)` expands to `9` or `10`, so the same command works on both Rocky versions.
- On Rocky, SQLite support (`pdo_sqlite` and `sqlite3`) is part of the `php-pdo` package.
- As with Ubuntu, install `php-cli` rather than `php`. On Rocky, the `php` package pulls in Apache.

If you'll connect to MySQL or MariaDB instead of SQLite, also install:
```sh
sudo dnf install -y php-mysqlnd
```
<br>

### C. Verify installation:
```sh
php -v
php -m | grep -i sqlite     # Should list pdo_sqlite and sqlite3
```
<br>

### D. Configure Upload Size (for Profile Image Support)
The built-in server uses the CLI configuration, so edit the CLI `php.ini`:

**Ubuntu & Debian**

The path includes your PHP version (for example `8.3`, `8.4`, or `8.5`). Check it with `php -v`, then edit:
```sh
sudo vi /etc/php/<version>/cli/php.ini
```

**Rocky Linux (RHEL-based)**
```sh
sudo vi /etc/php.ini
```

Not sure which file is in use? Run:
```sh
php --ini
```

Then modify the following settings:

| Setting | What it controls | Safe recommended value |
|:-------:|-------------|-------------|
| `upload_max_filesize` | Max size of a single uploaded file | `5M` (or `10M` if high-res image uploads) |
| `post_max_size` | Max size of total POST body (form fields + files) | `8M` (or `15M` if `upload_max_filesize` is `10M`) |
| `max_execution_time` | Max script run time (seconds) | `30` to `60` |
| `memory_limit` | Max memory a script can use | `128M` (or `256M` for image-heavy apps) |

These values should be set depending on what type of files are being uploaded. Files such as videos should be much higher. `post_max_size` should be greater than `upload_max_filesize`. Otherwise, you will get a corrupted token error instead.

Changes take effect the next time you start `php console serve`.

<br>

## 4. Install Composer <a id="composer"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Composer is required to manage PHP dependencies.
#### 1: Download and Install Composer
```sh
cd ~/
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 2: Verify Installation
```sh
composer -V
```
<br>

## 5. Install Node.js & NPM <a id="nodejs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Use NodeSource to install the latest LTS Node.js version.
#### 1: Add Node.js Repository
**Ubuntu & Debian**
```sh
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo bash -
```

**Rocky Linux (RHEL-based)**
```sh
curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
```
<br>

#### 2: Install Node.js & NPM
**Ubuntu & Debian**
```sh
sudo apt install -y nodejs
```

**Rocky Linux (RHEL-based)**
```sh
sudo dnf install -y nodejs
```
<br>

#### 3: Verify Installation
```sh
node -v
npm -v
```
<br>

## 6. Project Setup <a id="project-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Navigate to your preferred development directory and create the project:
#### 1. Create The Project
Run the following commands (replace my-app with the name of your project):

```sh
mkdir -p ~/repos
cd ~/repos
composer create-project chappy-php/chappy-php my-app
cd my-app
npm install
```
<br>

#### 2. Open Project
Open project with your favorite IDE (We use VSCode)

#### 3. Vite
Run:
```sh
npm run dev
```

#### 4. Run Project
Open a new terminal tab, change to your project directory, and run:
```sh
cd ~/repos/my-app
php console serve
```

Navigate to `http://localhost:8000`

<br>

## 7. Troubleshooting <a id="troubleshooting"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `could not find driver` Error
The PHP database extension for your connection isn't installed. Install `php-sqlite3` (Ubuntu/Debian) or `php-pdo` (Rocky) for SQLite, or `php-mysql` / `php-mysqlnd` for MySQL/MariaDB, then restart `php console serve`.
<br>

### B. Apache Was Installed Unexpectedly
The bare `php` package was installed instead of `php-cli`. If you don't want Apache on this machine, remove it:
```sh
sudo apt purge -y apache2 libapache2-mod-php*     # Ubuntu & Debian
sudo dnf remove -y httpd php                      # Rocky Linux
```
<br>

### C. Port 8000 Already in Use
Another process is using the port. Find it with:
```sh
ss -ltnp | grep 8000
```
Stop that process, or start the server on a different port if `php console serve` supports one.
<br>

### D. Rocky Linux 10 Won't Boot or Install
Rocky Linux 10 requires an x86-64-v3 CPU. On older hardware, use Rocky Linux 9. On a VM, change the CPU type to `host` (or another v3-capable model).