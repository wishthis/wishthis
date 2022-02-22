# wishthis

wishthis is a simple, intuitive and modern platform to create, manage and view
your wishes for any kind of occasion ([demo](https://wishthis.online)).

![View wishlist](/src/assets/img/wishlist-view.png "View wishlist")

Join the [Discord](https://discord.gg/WJaKrQd9)!

## Warning
wishthis is a work in progress and may break at any time. Do not rely on it and make sure you have backups of your data.

Use at your own risk.

## Requirements
* PHP 8

## Installation
1. Download the latest [release](https://github.com/grandeljay/wishthis/releases) and upload all files to your server
1. Follow the instructions of the installer

## Contributing
Install dependencies

### Composer
Use one of the following commands.

| Command                     | Description                         |
| --------------------------- | ----------------------------------- |
| `composer install`          | Install all dependencies.           |
| `composer install --no-dev` | Install only required dependencies. |

### NPM
Use one of the following commands.

| Command                                       | Description                         |
| --------------------------------------------- | ----------------------------------- |
| `npm install`                                 | Install all dependencies.           |
| `npm install --only=production --no-optional` | Install only required dependencies. |

### Theme changes
```
cd semantic
```

And then one of the following commands:
- `gulp build`
- `gulp watch`

For more information see: https://fomantic-ui.com/introduction/build-tools.html
