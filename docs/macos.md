<h1 style="font-size: 50px; text-align: center;">MacOS</h1>

## Table of contents
1. [Overview](#overview)
2. [Install Homebrew](#homebrew)
3. [Node.js](#nodejs)
4. [Development Setup](#dev)
5. [Optional: Local MariaDB/MySQL](#database)
6. [Troubleshooting](#troubleshooting)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide shows you how to set up the Chappy.php framework on macOS for local development using Homebrew and PHP's built-in development server (`php console serve`).

**Supported versions**

| Mac | macOS versions | Support |
|:----|:---------------|:--------|
| Apple Silicon (M1 or newer) | macOS 27 Golden Gate, 26 Tahoe, 15 Sequoia | ✅ Fully supported |
| Intel | macOS 26 Tahoe | ⚠️ Best effort (see note) |

> ⚠️ **Intel Macs:** macOS 26 Tahoe is the last macOS release for Intel, and Homebrew moved Intel Macs to its lowest support tier in September 2026. Homebrew still works on Intel, but some packages may need to compile from source (slow), and Homebrew plans to end Intel support in September 2027. These instructions should work, but Intel is no longer a primary target.

**Requirements**
- PHP 8.3+
- Composer
- Node.js & NPM

<br>

## 2. Install Homebrew <a id="homebrew"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* A. If not already installed, set up Homebrew using the command below:
```sh
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
The installer will also install Apple's Command Line Tools (including Git) if they're missing.

* B. Apple Silicon Extra Steps
    - When the installer finishes, it prints a **Next steps** section. Run those commands to add Homebrew to your path. They look like this:
```sh
echo >> ~/.zprofile
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
eval "$(/opt/homebrew/bin/brew shellenv)"
```

* C. Verify install:
```sh
brew --version
brew doctor
```
<br>

## 3. Node.js <a id="nodejs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### 1: Option 1 - Install via Homebrew (Recommended)
* A. Run:
```sh
brew install node
```

#### 2: Option 2: Install via NVM (Node Version Manager)
This allows you to manage multiple Node.js versions.
* A. Install NVM
```sh
brew install nvm
mkdir -p ~/.nvm
```

* B. Setup NVM
    - Add the following to your ~/.zshrc (or ~/.bashrc if using Bash):
```sh
export NVM_DIR="$HOME/.nvm"
[ -s "$(brew --prefix)/opt/nvm/nvm.sh" ] && . "$(brew --prefix)/opt/nvm/nvm.sh"
[ -s "$(brew --prefix)/opt/nvm/etc/bash_completion.d/nvm" ] && . "$(brew --prefix)/opt/nvm/etc/bash_completion.d/nvm"
```
`$(brew --prefix)` resolves to `/opt/homebrew` on Apple Silicon and `/usr/local` on Intel, so the same lines work on both.

* Reload shell configuration:
```sh
source ~/.zshrc
```

* C. Install Node.js using NVM
```sh
nvm install --lts
```

#### 3: Verify installation
* Run:
```sh
node -v
npm -v
```

* Expected output:
```sh
vXX.XX.X (Node.js version)
X.X.X (NPM version)
```
<br>

## 4. Development Setup <a id="dev"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### 1: PHP Setup
* A. Install PHP
```sh
brew install php
```
This installs Homebrew's current PHP release, which meets the PHP 8.3+ requirement. It includes the SQLite and MySQL drivers, so no extra extensions are needed.

* B. Verify install
```sh
php -v
```

* C. Configure PHP

Find your `php.ini` (the path includes your Homebrew prefix and PHP version):
```sh
php --ini
```

Then open the file listed as **Loaded Configuration File**. It will look like one of these:

**Apple Silicon:**
```sh
vi /opt/homebrew/etc/php/<version>/php.ini
```

**Intel:**
```sh
vi /usr/local/etc/php/<version>/php.ini
```

No `sudo` is needed because Homebrew files are owned by your user.

Then modify the following settings:

| Setting | What it controls | Safe recommended value |
|:-------:|-------------|-------------|
| `upload_max_filesize` | Max size of a single uploaded file | `5M` (or `10M` if high-res image uploads) |
| `post_max_size` | Max size of total POST body (form fields + files) | `8M` (or `15M` if `upload_max_filesize` is `10M`) |
| `max_execution_time` | Max script run time (seconds) | `30` to `60` |
| `memory_limit` | Max memory a script can use | `128M` (or `256M` for image-heavy apps) |

These values should be set depending on what type of files are being uploaded. Files such as videos should be much higher. `post_max_size` should be greater than `upload_max_filesize`. Otherwise, you will get a corrupted token error instead.

Changes take effect the next time you start `php console serve`.

> You do **not** need to run `brew services start php`. That starts PHP-FPM for use with a web server, which `php console serve` doesn't use.

#### 2: Setup Composer
* A. Run:
```sh
brew install composer
```

* B. Verify install. Run:
```sh
composer -V
```

#### 3: Setup and Run Project
* A. Run the commands (replace my-app with the name of your project):
```sh
mkdir -p ~/repos
cd ~/repos
composer create-project chappy-php/chappy-php my-app
cd my-app
npm install
```

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

## 5. Optional: Local MariaDB/MySQL <a id="database"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Skip this section if you're using SQLite. Use it if you want to develop against MySQL or MariaDB like a production server.

#### 1: Install and Start MariaDB
```sh
brew install mariadb
brew services start mariadb
```
`brew services` starts MariaDB now and on every login.

> Prefer MySQL? Use `brew install mysql` and `brew services start mysql` instead. Install one or the other, not both.

#### 2: Create the Application Database and User
Homebrew's MariaDB lets your macOS user log in as an administrator without a password:
```sh
mariadb
```

Create a database and a user for your app (replace the names and password):
```sql
CREATE DATABASE my_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'my_app_user'@'localhost' IDENTIFIED BY 'your-secure-password';
GRANT ALL PRIVILEGES ON my_app.* TO 'my_app_user'@'localhost';
EXIT;
```

For MySQL, log in with `mysql -u root` instead (Homebrew's MySQL root has no password by default), then run the same SQL.

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

#### 4: Database GUI (Optional)
phpMyAdmin requires a web server. For a lighter option, use a desktop client such as [Sequel Ace](https://sequel-ace.com/) (free) or [DBeaver](https://dbeaver.io/) and connect to `127.0.0.1:3306` with your application user.

<br>

## 6. Troubleshooting <a id="troubleshooting"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `brew: command not found` (Apple Silicon)
Homebrew's path setup wasn't run. Run the commands from Section 2B, then open a new terminal.
<br>

### B. Wrong PHP Version
If `php -v` shows an unexpected version, another PHP (for example from MAMP or an old install) is earlier in your path. Check which one is running:
```sh
which php
```
It should be `/opt/homebrew/bin/php` (Apple Silicon) or `/usr/local/bin/php` (Intel).
<br>

### C. Need a Specific PHP Version
Homebrew's `php` formula moves to new PHP releases over time. To pin a version (for example 8.4):
```sh
brew install php@8.4
brew unlink php
brew link --force --overwrite php@8.4
```
<br>

### D. Port 8000 Already in Use
Another process is using the port. Find it with:
```sh
lsof -i :8000
```
<br>

### E. Moving from XAMPP
XAMPP is no longer covered by this guide. Its newest release ships PHP 8.2, which is below Chappy.php's PHP 8.3 requirement. To migrate an existing XAMPP project:
1. Install PHP, Composer, and Node.js using this guide.
2. Export your database from XAMPP's phpMyAdmin.
3. Set up MariaDB (Section 5) and import the export:
```sh
mariadb my_app < export.sql
```
4. Move your project out of `htdocs` (for example to `~/repos/my-app`), set `APP_DOMAIN` to `/`, and run it with `php console serve`.