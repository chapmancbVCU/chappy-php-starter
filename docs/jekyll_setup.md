---
layout: default
title: "Setup With Jekyll"
parent: "User Guide"
nav_order: 1
---

# User Guide Setup
## Table of contents
1. [Overview](#overview)
2. [Install Ruby and Jekyll](#install)
3. [Docs Directory](#docs)
4. [Create or Update the Gemfile](#gem)
5. [Install Dependencies](#install-dependencies)
6. [Configure _config.yml](#configure-yaml)
7. [Serve Jekyll Locally](#serve-locally)
8. [Commit and Push to GitHub](#github)
9. [Enable GitHub Pages](#enable-github-pages)
10. [Verify Deployment](#deployment)
11. [(Optional) Custom Domain](#custom-domain)
12. [Troubleshooting](#troubleshooting)

<br>

## 1. Overview <a id="overview"></a>
This guide explains how to set up **Jekyll** to serve the PHP MVC framework's user guide both **locally** and on **GitHub Pages**, ensuring compatibility with GitHub’s environment.
<br>
<br>

## 2. Install Ruby and Jekyll <a id="install"></a>
Jekyll requires Ruby. Follow the steps based on your operating system:
<br>

#### Linux
```
gem install bundler jekyll
```
<br>

#### MacOS
* A. Install `rbnev` using Homebrew
  ```sh
  brew install rbenv ruby-build
  ```
  <br>

* B. Shell Configuration

  Zsh (MacOS default) - run:
  ```sh
  echo 'eval "$(rbenv init - zsh)"' >> ~/.zshrc
  source ~/.zshrc
  ```

  Bash users:
  ```sh
  echo 'eval "$(rbenv init - bash)"' >> ~/.bash_profile
  source ~/.bash_profile
  ```
  <br>

* C. Install Ruby (Latest Stable Version)
  ```sh
  rbenv install 3.2.2
  rbenv global 3.2.2

  ```
  <br>

* D. Install Bundler and Jekyll
  ```sh
  gem install bundler
  gem install jekyll
  ```
  <br>

#### Windows
* A. Install **Ruby** from [RubyInstaller](https://rubyinstaller.org/).
* B. Open a terminal (Git Bash or Command Prompt with Ruby) and run:
   ```sh
   gem install bundler jekyll
   ```
<br>

## 3. Docs Directory <a id="docs"></a>
Navigate to the `docs/` Directory

Since the user guide is stored in `docs/` at the project root, navigate to that directory:
```sh
cd path/to/your/project/docs
```
<br>

## 4. Create or Update the Gemfile <a id="gem"></a>
The `Gemfile` manages Jekyll dependencies. Ensure it contains:

```ruby
source "https://rubygems.org"
```

#### Use the GitHub Pages gem, which includes Jekyll
```ruby
gem "github-pages", group: :jekyll_plugins
```

#### The Just the Docs theme is loaded with jekyll-remote-theme (set in `_config.yml`).
```ruby
group :jekyll_plugins do
  gem "jekyll-feed", "~> 0.12"
  gem "jekyll-remote-theme"
end
```

#### Windows and JRuby fixes
```ruby
platforms :mingw, :x64_mingw, :mswin, :jruby do
  gem "tzinfo", ">= 1", "< 3"
  gem "tzinfo-data"
end

gem "wdm", "~> 0.1", platforms: [:mingw, :x64_mingw, :mswin]
gem "http_parser.rb", "~> 0.6.0", platforms: [:jruby]
```
<br>

## 5. Install Dependencies <a id="install-dependencies"></a>
Before installing, remove any old dependencies:
```sh
rm -rf Gemfile.lock
```

Now install the correct versions:
```sh
bundle install
```
<br>

## 6. Configure _config.yml <a id="configure-yaml"></a>
Modify `docs/_config.yml` to ensure the correct setup:

```yaml
title: "My PHP MVC Framework Guide"
description: "User guide for my custom PHP MVC framework."
remote_theme: just-the-docs/just-the-docs@v0.12.0
plugins:
  - jekyll-remote-theme
  - jekyll-feed
baseurl: "/your-repo-name" # Set to "" if this is a user/org site
url: "https://yourusername.github.io"

# Ensure Jekyll serves Markdown files
include:
  - "*.md"

# Exclude unnecessary files
exclude:
  - "Gemfile"
  - "Gemfile.lock"
  - "README.md"
  - "vendor"
  - "node_modules"
```

#### Base URL Considerations:
- **For user/org sites (`yourusername.github.io`)**, set:
  ```yaml
  baseurl: ""
  ```
- **For project sites (`yourusername.github.io/your-repo-name`)**, set:
  ```yaml
  baseurl: "/your-repo-name"
  ```
<br>

## 7. Serve Jekyll Locally <a id="serve-locally"></a>
To preview the guide locally from within the docs directory:
```sh
bundle exec jekyll serve --livereload
```
or under project root:
```sh
php console serve:docs
```

Then open:
- **For project sites:** `http://localhost:4000/your-repo-name/`
- **For user/org sites:** `http://localhost:4000/`
<br>

## 8. Commit and Push to GitHub <a id="github"></a>
Once everything looks good, commit and push the changes:
```sh
git add .
git commit -m "Set up Jekyll for framework guide"
git push origin main
```
<br>

## 9. Enable GitHub Pages <a id="enable-github-pages"></a>
1. Go to your repository on **GitHub**.
2. Navigate to **Settings > Pages**.
3. Under **Source**, select:
   - `GitHub Actions` **(recommended)**

      OR
   - `GitHub Pages` with the `main` branch and `/docs` folder.
4. Click **Save**.

Your site will be available at:
- `https://yourusername.github.io/your-repo-name/`

    OR
- `https://yourusername.github.io/` (for user/org sites).
<br>
<br>

## 10. Verify Deployment <a id="deployment"></a>
After a few minutes, visit:
- **For project pages:** `https://yourusername.github.io/your-repo-name/`
- **For user/org pages:** `https://yourusername.github.io/`

To check for issues:
```sh
bundle exec github-pages health-check
```
<br>

## 11. (Optional) Custom Domain <a id="custom-domain"></a>

If using a custom domain:

1. Add a `CNAME` file inside `docs/` containing your domain.
2. Update **DNS settings** to point to GitHub’s IPs.
3. Enable **HTTPS** in **GitHub Pages settings**.
<br>
<br>

## 12. Troubleshooting <a id="troubleshooting"></a>
In my particular case I had to export gems using the following commands:
```sh
echo 'export GEM_HOME=$HOME/gems' >> ~/.bashrc
echo 'export PATH="$HOME/gems/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```