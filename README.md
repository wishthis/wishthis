![wishthis logo](/src/assets/img/logo-readme.svg "wishthis logo")

# Make a wish

wishthis is a simple, intuitive and modern wishlist platform to create, manage and view
your wishes for any kind of occasion ([demo](https://wishthis.online)).

## Screenshots
| Home                                                 | Wishlists                                                           |
| ---------------------------------------------------- | ------------------------------------------------------------------- |
| ![Home](/src/assets/img/screenshots/home.png "Home") | ![Wishlists](/src/assets/img/screenshots/wishlists.png "Wishlists") |

## Join the conversation

[![Discord](https://badgen.net/discord/members/WrUXnpNyza/?label=Discord&color=purple&icon=discord)](https://discord.gg/WrUXnpNyza)
[![Matrix](https://badgen.net/matrix/members/wishthis/matrix.org)](https://matrix.to/#/#wishthis:matrix.org)

## Available in 3 different locales
:gb: English (United Kingdom),
:us: English (United States),
:de: German  (Germany)

## Requirements
* Apache or Nginx (pretty URLs don't work on Nginx)
* PHP 8.1

## Installation

### Git (recommended)
```
git clone -b stable https://github.com/grandeljay/wishthis.git
```

Note: after pulling updates for a new version you might be prompted to update the database schema in the wishthis user interface (if necessary). Make sure you are logged in.

### Manual
Download the code using the [stable branch](https://github.com/grandeljay/wishthis/tree/stable) and upload it to your server.

Note: You will have to manually update wishthis by replacing all files with the changes from the `stable` branch.

## Contributing

### As a translator
https://www.transifex.com/wishthis/wishthis/

### As a developer
Install dependencies

#### Composer
Use one of the following commands.

| Command                     | Description                         |
| --------------------------- | ----------------------------------- |
| `composer install`          | Install all dependencies.           |
| `composer install --no-dev` | Install only required dependencies. |

#### NPM
Use one of the following commands.

| Command                                       | Description                         |
| --------------------------------------------- | ----------------------------------- |
| `npm install`                                 | Install all dependencies.           |
| `npm install --only=production --no-optional` | Install only required dependencies. |

#### Theme changes
```
cd semantic
```

And then one of the following commands:
- `gulp build`
- `gulp watch`

For more information see: https://fomantic-ui.com/introduction/build-tools.html

## Style

### PHP
| Language | Style |
| -------- | ----- |
| PHP      | PSR-12 |
