<h1 style="font-size: 50px; text-align: center;">Windows</h1>

## Table of contents
1. [Overview](#overview)
2. [Install Chocolatey (Recommended)](#chocolatey)
3. [Install PHP](#php)
4. [PHP Configuration](#php-configuration)
5. [Install Composer](#composer)
6. [Install Node.js and npm](#nodejs)
7. [Install 7-Zip](#7zip)
8. [Project Setup](#project-setup)
9. [Optional: Local MariaDB](#database)
10. [Image Seeding With Windows](#seeding)
11. [Troubleshooting](#troubleshooting)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide shows you how to set up the Chappy.php framework on Windows for local development using standalone PHP and PHP's built-in development server (`php console serve`).

**Supported versions**
- Windows 11 (x64)
- Windows 10 reached end of support in October 2025. These steps should still work there, but it is no longer a target.

**Requirements**
- PHP 8.3+
- Composer
- Node.js & npm
- 7-Zip (used by Composer to extract packages)

> **About XAMPP:** XAMPP is no longer covered by this guide. Its newest release ships PHP 8.2, which is below Chappy.php's PHP 8.3 requirement. If you're moving an existing XAMPP project, see Troubleshooting E.

> **PowerShell vs Command Prompt:** Either works. If `npm` commands fail in PowerShell with "running scripts is disabled on this system," see Troubleshooting A.

<br>

## 2. Install Chocolatey (Recommended) <a id="chocolatey"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chocolatey is a package manager for Windows. It installs PHP, Composer, Node.js, and 7-Zip with one command each and sets up your PATH for you. Every section below also includes manual install steps if you prefer not to use it.

* A. Open **PowerShell as Administrator** (Start Menu → type "PowerShell" → right-click → **Run as administrator**).
* B. Run:
```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```
* C. Close and reopen your Administrator terminal, then verify:
```powershell
choco -v
```

All `choco install` commands in this guide must be run from an **Administrator** terminal.

<br>

## 3. Install PHP <a id="php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### Option A: Install with Chocolatey (Recommended)
```powershell
choco install php -y
```
- PHP is installed to `C:\tools\php<version>` (for example `C:\tools\php84`) and added to your PATH automatically.
- Installing Composer with Chocolatey (Section 5) also installs PHP as a dependency, so you can skip straight to Section 5 if you like. Either way, you still need to complete **Section 4** to enable extensions.

<br>

#### Option B: Manual Install
* A. **Install the Visual C++ Redistributable**
    - PHP for Windows requires the latest **Microsoft Visual C++ Redistributable (x64)**. Download it from Microsoft's site if it isn't already installed (most systems have it).

* B. **Download PHP**
    - Go to the official [Windows PHP downloads](https://windows.php.net/download).
    - Download the **latest PHP 8.4 or newer, x64 Non Thread Safe (NTS) zip package**. NTS is the right build for the command line and the built-in server.

* C. **Extract and Set Up PHP**
    - Extract the downloaded ZIP to `C:\php`.
    - Rename `php.ini-development` to `php.ini` in `C:\php`.
    - Add `C:\php` to the **system PATH**:
        - Open **Start Menu**, search for **"Environment Variables"**.
        - Under **System Variables**, edit the **Path** variable.
        - Click **New**, then add `C:\php`.
    - Close and reopen any open terminals so they see the new PATH.

<br>

#### Verify Installation
Open a **new** terminal and run:
```sh
php -v
```
If PHP 8.3 or newer is displayed, it's correctly installed. If `php` isn't recognized, see Troubleshooting B.

<br>

## 4. PHP Configuration <a id="php-configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Find your PHP configuration file:
```sh
php --ini
```
Open the file listed as **Loaded Configuration File**. It will be one of these:

**PHP installed with Chocolatey**
```sh
C:\tools\php<version>\php.ini
```

**PHP installed manually**
```sh
C:\php\php.ini
```

#### A. Set the Extension Directory (Manual Install Only)
Find the `extension_dir` line, uncomment it, and set it to an absolute path:
```ini
extension_dir = "C:\php\ext"
```

#### B. Enable Extensions
Uncomment (remove the leading `;`) the following lines:
```ini
extension=curl
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=pdo_sqlite
extension=sqlite3
extension=zip
```

Verify they loaded:
```sh
php -m
```

#### C. Upload and Resource Limits
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

## 5. Install Composer <a id="composer"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### Option A: Install with Chocolatey (Recommended)
```powershell
choco install composer -y
```

#### Option B: Manual Install
* A. **Download Composer Installer**
   - Visit [Composer's official site](https://getcomposer.org/download/) and download the Windows installer (`Composer-Setup.exe`).

* B. **Run the Installer**
   - During installation, ensure it detects your `php.exe` (for example `C:\php\php.exe`).

#### Verify Installation
Open a **new** terminal and run:
```sh
composer -V
```
You should see the Composer version output.

<br>

## 6. Install Node.js and npm <a id="nodejs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### Option A: Install with Chocolatey (Recommended)
```powershell
choco install nodejs-lts -y
```

#### Option B: Manual Install
- Go to the [Node.js official site](https://nodejs.org/).
- Download and install the **LTS version** (includes npm).

#### Verify Installation
Open a **new** terminal and check versions:
```sh
node -v
npm -v
```
These should return the installed versions.

<br>

## 7. Install 7-Zip <a id="7zip"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### Option A: Install with Chocolatey (Recommended)
```powershell
choco install 7zip -y
```

#### Option B: Manual Install
* A. **Download the Installer**
    - Go to the official 7-Zip website: 👉 `https://www.7-zip.org/download.html`
    - Download the **64-bit Windows x64** installer.
* B. **Run the installer**:
    - Open the downloaded `.exe` file.
    - Click Install.
    - Once finished, click Close.
* C. **Add 7-Zip to your PATH**
    - The manual installer doesn't add itself to PATH. Add `C:\Program Files\7-Zip` to the **system PATH** using the same steps as in Section 3.

#### Verify Installation
Open a **new** terminal and run:
```sh
7z
```
If installed correctly, it will show 7-Zip's version and help menu.

<br>

## 8. Project Setup <a id="project-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* A. Open a terminal (it does **not** need to be Administrator) and run the commands below (replace my-app with the name of your project):
```sh
mkdir %USERPROFILE%\repos
cd %USERPROFILE%\repos
composer create-project chappy-php/chappy-php my-app
cd my-app
npm install
```
In PowerShell, use `$HOME\repos` instead of `%USERPROFILE%\repos`.

* B. Open the project with your preferred IDE. We use VSCode.
* C. In the terminal, from your project directory, run:
```sh
npm run dev
```
* D. In a new terminal tab, from your project directory, run:
```sh
php console serve
```
* E. Navigate to `http://localhost:8000` in your preferred web browser.

<br>

## 9. Optional: Local MariaDB <a id="database"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Skip this section if you're using SQLite. Use it if you want to develop against MySQL or MariaDB like a production server.

#### 1: Install MariaDB
* A. Download the **MariaDB Server** MSI installer for Windows from [mariadb.org/download](https://mariadb.org/download/).
* B. Run the installer:
    - Set a **root password** when prompted, and remember it.
    - Leave **Install as service** checked so MariaDB starts automatically.
    - Keep the default port `3306`.
    - **HeidiSQL** (a database GUI) is included. Keep it selected if you want a phpMyAdmin-style tool.
* C. If Windows asks whether to allow `mysqld` on public and private networks, select **Private networks only**.

#### 2: Create the Application Database and User
Open **Command Prompt (MariaDB)** from the Start Menu (or HeidiSQL's query tab) and log in:
```sh
mariadb -u root -p
```

Create a database and a user for your app (replace the names and password):
```sql
CREATE DATABASE my_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'my_app_user'@'localhost' IDENTIFIED BY 'your-secure-password';
GRANT ALL PRIVILEGES ON my_app.* TO 'my_app_user'@'localhost';
EXIT;
```

#### 3: Update the `.env` File
```php
# Set to mysql or mariadb
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_app
DB_USER=my_app_user
DB_PASSWORD=your-secure-password
```

Then run migrations:
```sh
php console migrate
```

<br>

## 10. Image Seeding With Windows <a id="seeding"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

To use image seeding you will need a CA certificate bundle so PHP can make HTTPS requests.

1. Go to `https://curl.se/docs/caextract.html`
2. Download the latest bundle (`cacert.pem`).
3. Create a `certs` folder inside your PHP directory and move the file there. For example:
    - Manual install: `C:\php\certs\cacert.pem`
    - Chocolatey install: `C:\tools\php<version>\certs\cacert.pem`
4. Make sure the filename is `cacert.pem`.
5. Open your `php.ini` file (run `php --ini` to find it) and add the following lines, using your actual path:

    ```ini
    curl.cainfo="C:\php\certs\cacert.pem"
    openssl.cafile="C:\php\certs\cacert.pem"
    ```

6. Stop `php console serve` if it's running, then start it again. A full Windows restart isn't needed.

<br>

## 11. Troubleshooting <a id="troubleshooting"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. "running scripts is disabled on this system" (PowerShell)
PowerShell's default execution policy blocks npm's PowerShell scripts. Either run the command from **Command Prompt** instead, or allow local scripts for your user (one-time):
```powershell
Set-ExecutionPolicy -Scope CurrentUser RemoteSigned
```
<br>

### B. `php`, `composer`, `node`, or `7z` Is Not Recognized
The program isn't on your PATH, or your terminal was opened before it was added. Close and reopen the terminal first. If it still isn't found, check the PATH entries from Sections 3 and 7, and restart Windows if needed.
<br>

### C. `could not find driver` Error
The PHP database extension isn't enabled. Make sure `extension=pdo_sqlite` (SQLite) or `extension=pdo_mysql` (MySQL/MariaDB) is uncommented in `php.ini` (Section 4), and on manual installs that `extension_dir` is set. Confirm with:
```sh
php -m
```
<br>

### D. Port 8000 Already in Use
Another process is using the port. Find it with:
```sh
netstat -ano | findstr :8000
```
The last column is the process ID, which you can end in Task Manager.
<br>

### E. Moving from XAMPP
To migrate an existing XAMPP project:
1. Install PHP, Composer, and Node.js using this guide.
2. Export your database from XAMPP's phpMyAdmin (`http://localhost/phpmyadmin` → select database → **Export**).
3. Uninstall XAMPP, or at least remove `C:\xampp\php` from your PATH so it doesn't shadow the new PHP.
4. Set up MariaDB (Section 9) and import the export:
```sh
mariadb -u root -p my_app < export.sql
```
5. Move your project out of `C:\xampp\htdocs` (for example to `%USERPROFILE%\repos\my-app`), set `APP_DOMAIN` to `/`, and run it with `php console serve`.