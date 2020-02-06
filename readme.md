# GetPAA - Simple scraping People Also Ask 
![GitHub](https://img.shields.io/scrutinizer/build/g/drogbadvc/GetPAA)
![GitHub](https://img.shields.io/github/languages/count/drogbadvc/GetPAA)
![GitHub](https://img.shields.io/github/languages/top/drogbadvc/GetPAA)
![GitHub](https://img.shields.io/scrutinizer/quality/g/drogbadvc/GetPAA)

## About
GetPAA is a tool for scraping People Also Ask from Google French in just one click with the possibility of choosing the depth.
 
Ability to switch to English in configuration files.

## Requirements

**Local Environment**
* PHP >=7.2 (https://www.php.net/downloads.php)
* NodeJS >=8.16.0 (https://nodejs.org/fr/)
* Composer (https://github.com/composer/composer)

**NPM-Packages**
* Puppeteer (https://www.npmjs.com/package/puppeteer)
* Minimist (https://www.npmjs.com/package/minimist)

## Demo && Preview

![A screenshot of GetPaa](https://i.imgur.com/XzaHgYw.png)

**Visit the [demo youtube](https://www.youtube.com/watch?v=AKoTRefeJsc)**

## Install

Download ZIP project or Git Clone, extract in to your www or HTDOCS directory (web server)

Check node,npm  and composer are installed in Terminal :

```sh
node -v
```

```sh
npm -v
```

```sh
composer about
```

In Terminal, install npm packages dependencies :

```sh
npm install
```

```sh
composer install
```

## Installation NodeJS (v13.x)

```sh
# Using Ubuntu
curl -sL https://deb.nodesource.com/setup_13.x | sudo -E bash -
sudo apt-get install -y nodejs
```

## Installation NPM (v6.13)

```sh
sudo apt-get -qq update 
sudo apt-get --yes -qq --allow-downgrades install npm;
```

## Installation Composer && dependencies

```sh
# Composer && php7
sudo apt-get -qq update
sudo apt-get --yes -qq --allow-downgrades install composer;
# ext-mbstring
sudo apt-get --yes -qq --allow-downgrades install php-mbstring;
# ext-dom
sudo apt-get --yes -qq --allow-downgrades install php-xml;
```


## Usage

Open in browser your web server.
```sh
php -S localhost:8000
```
Enjoy!!

## License

Source code are under the GPL v3 License unless dependencies.