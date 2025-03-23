<h1 style="font-size: 50px; text-align: center;">Linux Standalone PHP</h1>

## Table of contents
1. [Overview](#overview)
2. [Install System Dependencies](#dependencies)
3. [Install PHP 8.4](#php)
4. [Install Composer](#composer)
5. [Install Node.js & NPM](#nodejs)
6. [Project Setup](#project-setup)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide walks through setting up your the Chappy.php framework on Ubuntu (22.04 LTS), Debian (LMDE), and RHEL (Rocky Linux 9) based distributions without requiring XAMPP, Nginx, or Apache. The framework is self-hosted using PHP’s built-in development server (php console serve).

**Requirements**
- PHP 8.4
- Composer
- Node.js & NPM
- Git (for cloning the repository)

<br>

## 2. Install System Dependencies <a id="dependencies"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
First, update your system and install essential dependencies:

**Debian (LMDE)**
```sh
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip
```

**RHEL**
```sh
sudo dnf update -y
sudo dnf install -y curl wget git unzip
```

**Ubuntu**
```sh
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip software-properties-common
```
<br>

## 3. Install PHP 8.4 <a id="php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. Debian (LMDE)**
#### 1. Add the SURY repository (trusted PHP repo for Debian)
```sh
sudo apt install -y lsb-release apt-transport-https ca-certificates wget gnupg2
```

Then import the GPG key:
```sh
wget -qO - https://packages.sury.org/php/apt.gpg | sudo tee /etc/apt/trusted.gpg.d/php.gpg >/dev/null
```

Now add the repo to your sources list:
```sh
echo "deb https://packages.sury.org/php/ bookworm main" | sudo tee /etc/apt/sources.list.d/php.list
```
<br>

#### 2. Update and install PHP 8.4
```sh
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.4 php8.4-cli php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-sqlite3 php8.4-bcmath
```
<br>

### B. RHEL
```sh
sudo dnf install -y epel-release
sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-9.rpm
sudo dnf module list php                    # List available PHP modules
sudo dnf module enable php:remi-8.4 -y      # Enabled PHP 8.3 from Remi repo
sudo dnf install -y php php-cli php-mbstring php-xml php-curl php-zip php-sqlite3 php-bcmath
```
<br>

### C. Ubuntu
```sh
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.4 php8.4-cli php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-sqlite3 php8.4-bcmath
```
<br>

Verify installation:
```sh
php -v
```
<br>

## 4. Install Composer <a id="composer"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Composer is required to manage PHP dependencies.
#### 1: Download and Install Composer
```sh
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 2: Verify Installation
```sh
composer -v
```
<br>

## 5. Install Node.js & NPM <a id="nodejs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Use NodeSource to install the latest stable Node.js version.
#### 1: Add Node.js Repository
**Debian (LMDE) and Ubuntu**
```sh
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
```

**RHEL**
```sh
curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
```
<br>

#### 2: Install Node.js & NPM
**Debian (LMDE) and Ubuntu**
```sh
sudo apt install -y nodejs
```

**RHEL**
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
Navigate to your preferred development directory and clone the project:
#### 1. Cloning The Project
```sh
mkdir repos
cd repos/
git clone git@github.com:chapmancbVCU/chappy-php.git
cd chappy-php/
```
<br>

#### 2. Install
Run:
```sh
composer run install-project
```
<br>

#### 3. Run Project
* A. Run:
```sh
php console serve
```

* B. Navigate to `http://localhost:8000`