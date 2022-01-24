# wishthis

A simple wishlist plattform ([demo](https://wishthis.online)).

## Warning
wishthis is a work in progress and may break at any time. Do not rely on it and make sure you have backups of your data.

Use at your own risk.

## Requirements
* PHP 8

## Screenshots
![Home](/includes/assets/img/home.png "Home")
![Create a wishlist](/includes/assets/img/wishlist-create.png "Create a wishlist")
![Add a product](/includes/assets/img/wishlist-product-add.png "Add a product")

## Installation
1. Download the latest [release](https://github.com/grandeljay/wishthis/releases) and upload all files to your server
1. Follow the instructions of the installer

## Contributing
Install dependencies

### Composer
- `composer install`
- `composer install --dev`
- `composer install --no-dev`

### NPM
Use one of the following commands.

- `npm install`
- `npm install --only=development`
- `npm install --only=production --no-optional`

### Theme changes
```
cd semantic
```

And then one of the following commands:
- `gulp build`
- `gulp watch`

For more information see: https://fomantic-ui.com/introduction/build-tools.html
