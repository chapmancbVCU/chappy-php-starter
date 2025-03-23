<h1 style="font-size: 50px; text-align: center;">MacOS</h1>

## Table of contents
1. [Overview](#overview)
2. [Install Homebrew](#homebrew)
3. [Node.js](nodejs)
4. [Development Setup](#dev)
5. [XAMPP](#xampp)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide shows you how to setup this framework on MacOS.

## 2. Install Homebrew <a id="homebrew"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* A. If not already installed, setup Homebrew using the command below:
```sh
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

* B. Apple Silicon Extra Steps
    - Ensure you run the 3 commands required after the setup process is finished to setup your path.
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
```

* B. Setup NVM
    - Add the following to your ~/.zshrc (or ~/.bashrc if using Bash):
```sh
export NVM_DIR="$HOME/.nvm"
[ -s "/opt/homebrew/opt/nvm/nvm.sh" ] && . "/opt/homebrew/opt/nvm/nvm.sh"
[ -s "/opt/homebrew/opt/nvm/etc/bash_completion" ] && . "/opt/homebrew/opt/nvm/etc/bash_completion"
```

    - Reload shell configuration:
```sh
source ~/.zshrc
```

* C. Install Node.js using NVM
```sh
nvm install --lts
```

* D. Verify installation
    - Run
```sh
node -v
npm -v
```

   - Expected output:
```sh
vXX.XX.X (Node.js version)
X.X.X (NPM version)
```
<br>

## 4. Development Setup <a id="dev"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### 1: PHP Setup
* A. Install PHP
    - Run:
```sh
brew install php
```

* B. Verify install
    - Run:
```sh
php -v
```

* C: Start PHP as a service
    - Run:
```sh
brew services start php
```

#### 2: Setup Composer
* A. Run:
```sh
brew install composer
```

* B. Verify install.  Run:
```sh
composer -v
```

#### 3: Setup and Run Project
* A. Cone the project:
```sh
git clone git@github.com:chapmancbVCU/chappy-php.git
```

* B. cd into project and run the command:
```sh
composer run install-project
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
<br>

## 5. XAMPP <a id="xampp"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Things to consider
- When using XAMPP you should install XAMPP first before setting up composer.
- By default, XAMPP stores all web files in `/Applications/XAMPP/htdocs/`.  
- **We recommend moving `htdocs` outside `/Applications/XAMPP/`** to avoid losing files when upgrading XAMPP.
- For example, you can move it to `~/Sites/htdocs/` and update Apache’s config.
- Updates to composer packages may require you to upgrade XAMPP.
<br>

#### 1: Setup
* A. Open browser and go to https://www.apachefriends.org/ and download **XAMPP for MacOS**.
* B. Run installer using default options.

#### 2: Setup Composer
* A. Install **Composer**.
    - Run:
```sh
brew install composer
```
    - Verify install.  Run:
```sh
composer -v
```

#### 3: Project Setup
* A. Navigate to `Applications/XAMPP/htdocs` or your alternate location for `htdocs` using the Terminal.
* B. Cone the project inside the `htdocs` directory:
```sh
git clone git@github.com:chapmancbVCU/chappy-php.git
```
* C. cd into project directory and run:
```sh
composer run install-project
```

* D. Open manager-osx.
* E. Within the manager-osx start all services.
* F. Open project in your preferred editor.  We use VSCode.
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
DB_PASSWORD=    # Sometimes uses root as password
```
* J. Run the command:
```sh
npm run dev
```
* K. Open browser and navigate to `http://localhost/chappy-php/home`.
<br>

#### 4: Ensure XAMPP Starts on Boot
To ensure XAMPP services start automatically, open Terminal and run:
```sh
sudo launchctl load -w /Library/LaunchDaemons/org.apache.httpd.plist
sudo launchctl load -w /Library/LaunchDaemons/org.mysql.mysqld.plist
```

#### 5: Moving XAMPP's Root Directory (htdocs)
By default, XAMPP's htdocs directory is located inside /Applications/XAMPP/htdocs. However, for easier management and to prevent data loss when updating XAMPP, it's recommended to move it to another location.

Watch this video tutorial to learn how to move the root directory properly:

📺 How to Change XAMPP Root Directory on MacOS [YouTube](https://www.youtube.com/watch?v=cWPSBbwmQFE)