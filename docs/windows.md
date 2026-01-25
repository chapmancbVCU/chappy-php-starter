<h1 style="font-size: 50px; text-align: center;">Windows</h1>

## Table of contents
1. [Overview](#overview)
2. [Common](#common)
3. [XAMPP](#xampp)
4. [PHP Configuration](#php-configuration)
5. [Standalone PHP](#php)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide shows you how to setup this framework on Windows.  There are two ways to achieve this as described below.
1. XAMPP
2. Install only dependencies needed with standalone PHP.

<br>

## 2. Common <a id="common"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### 1: Install Composer - Chocolatey Install Highly Recommended (Skip until later if installing XAMPP)
* A. **Download Composer Installer**  
   - Visit [Composer's official site](https://getcomposer.org/download/) and download the Windows installer.

* B. **Run the Installer**  
   - During installation, ensure it detects `php.exe` in `C:\php`.

* C. **Verify Installation**  
   - Open **Command Prompt** and run:
     ```sh
     composer -V
     ```
   - You should see the Composer version output.

OR
 
Install with Chocolatey
```powershell
choco install composer -y
```

<br>

#### 2: Install Node.js and npm
* A. **Download Node.js**  
   - Go to [Node.js official site](https://nodejs.org/).
   - Download and install the **LTS version** (includes npm).

* B. **Verify Installation**  
   - Open **Command Prompt** and check versions:
     ```sh
     node -v
     npm -v
     ```
   - These should return the installed versions.

OR

Install with Chocolatey
```powershell
choco install node-lts
```
#### 3. Install 7zip
* A. **Download the Installer**
    - Go to the official 7-Zip website: 👉 `https://www.7-zip.org/download.html`
    - Choose the right version for your architecture.
* B. **Run the installer**:
    - Open the downloaded `.exe` file.
    - Click Install.
    - Once finished, click Close.
* C. **Verify Installation**
    - Open the **Command Prompt** (`Win + R → type "cmd" → Enter`).
    - Run:
    ```powershell
    7z
    ```
    - If installed correctly, it will show 7-Zip's version and help menu.
    
OR

Install with Chocolatey:
```powershell
choco install 7zip -y
```

<br>

## 3. XAMPP <a id="xampp"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### 1: Setup
* A. Open browser and go to https://www.apachefriends.org/ and download **XAMPP for Windows**.
* B. Select download location and run installer using default options.
* D. If you get a dialog box asking "Do you want to allow public and private networks to access this app?" for Apache select **Allow**
* E. Install **Composer** using the instructions described above.  We recommend using Chocolatey.
* F. Ensure PHP for XAMPP appears lower in list for SYSTEM PATH.
* G. `cd` to `C:\xampp\htdocs` using the terminal.
* H. Run the command (replace my-app with the name of your project):

```powershell
composer create-project chappy-php/chappy-php my-app
```

* If permission issues arise, running CMD as Administrator might be needed.

<br>

#### 2. Using with XAMPP
* A. Open XAMPP Control Panel.
* B. Start **Apache** and **MySQL**
* C. If you get a dialog box asking "Do you want to allow public and private networks to access this app?" for mysqld select **Allow**
* D. In your browser navigate to `localhost/phpmyadmin`
* In the left panel click on the **New** link.
* E. In the main panel under **Create Database** enter the name for your database.
* F. Click create.
* G. Open the .env file.
* H. Set `APP_DOMAIN` TO `http://localhost/chappy-php/`.  If you renamed your project directory then the second portion of the URL must match.  The URL must have the last forward slash.  Otherwise, the page and routing will not work correctly.
* I. Update the database section:
```php
# Set to mysql or mariadb for production
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
# Set to your database name for production
DB_DATABASE=chappy
DB_USER=root
# DB_PASSWORD=
```
* J. Run the command:
```sh
npm run dev
```
* K. Open browser and navigate to `http://localhost/chappy-php/home`.

<br>

## 4. PHP Configuration <a id="php-configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Edit your PHP configuration file:

**PHP with Composer**
```sh
C:\php\php.ini
```

**PHP with XAMPP**
```sh
C:\xampp\php\php.ini
```

Uncomment the following lines:
```rust
extension=pdo_mysql
extension=pdo_sqlite
```

Then modify the following settings:

| Setting | What it controls | Safe recommended value |
|:-------:|-------------|-------------|
| `upload_max_filesize` | Max size of a single uploaded file | `5M` (or `10M` if high-res image uploads) |
| `post_max_size` | Max size of total POST body (form fields + files) | `8M` (or `15M` if `upload_max_filesize` is `10M`) |
| `max_execution_time` | Max script run time (seconds) | `30` to `60` |
| `memory_limit` | Max memory a script can use | `128M` (or `256M` for image-heavy apps) | 

These value should be set depending on what type of files being uploaded.  Files such as videos should be much higher.   `post_max_size` should be greater than `upload_max_filesize`.  Otherwise, you will get a corrupted token error instead.

<br>

## 5. Standalone PHP <a id="php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### 1: Install PHP 8.4+
* A **Install with Composer**
    - Install Composer using Chocolately as described above.  PHP, which is a dependency will automatically be installed.  When setting up as standalone without XAMPP you can skip to **Setup the Project**.  If you change your mind later on, you will need to remove both Composer and PHP along with any values set in the SYSTEM path.  Installing PHP with this method will install PHP 8.4 at `C:\tools\php<ver_number>`.

OR

* B. **Download PHP**
    - Go to the official [Windows PHP downloads](https://windows.php.net/download).
    - Download the **latest PHP 8.4+ (Thread Safe) zip package**.
   
* C. **Extract and Set Up PHP**  
    - Extract the downloaded ZIP to `C:\php`.
    - Rename `php.ini-development` to `php.ini` in `C:\php`.
    - Open `php.ini` and enable necessary extensions:
```ini
extension=sqlite3
extension=mbstring
extension=openssl
```
    - Add `C:\php` to the **system PATH**:
        - Open **Start Menu**, search for **"Environment Variables"**.
        - Under **System Variables**, edit the **Path** variable.
        - Click **New**, then add `C:\php`.

    - After adding to PATH you should restart your computer.
* D. **Verify Installation**  
    - Open **Command Prompt (cmd)** and run:
```sh
php -v
```
    - If PHP 8.4+ is displayed, it's correctly installed.
* E. **Install Composer**
    - Refer to instruction in the **Common** section for installing from Composer.

<br>

#### 2. Setup The Project
* A. Install **Composer** using the instructions described above.
* B. Run the command (replace my-app with the name of your project):
```powershell
composer create-project chappy-php/chappy-php my-app
```

* C. Open the project with your preferred IDE.  We use VScode.
* D. In the terminal run the command:
```sh
php console serve
```
* E. In a new terminal tab run the command:
```sh
npm run dev
```
* F. Navigate to `localhost:8000` in your preferred web browser.
