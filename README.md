![wishthis logo](/src/assets/img/logo-readme.svg "wishthis logo")

# Make a wish

wishthis is a simple, intuitive and modern wishlist platform to create, manage and view
your wishes for any kind of occasion ([demo](https://wishthis.online)).

## :desktop_computer: Screenshots
| Home                                                 | Wishlists                                                           |
| ---------------------------------------------------- | ------------------------------------------------------------------- |
| ![Home](/src/assets/img/screenshots/home.png "Home") | ![Wishlists](/src/assets/img/screenshots/wishlists.png "Wishlists") |

## :family_man_man_boy: Join the conversation

[![Discord](https://badgen.net/discord/members/WrUXnpNyza/?label=Discord&color=purple&icon=discord)](https://discord.gg/WrUXnpNyza)
[![Matrix](https://badgen.net/matrix/members/wishthis/matrix.org)](https://matrix.to/#/#wishthis:matrix.org)

## :earth_africa: Available in 3 different locales
:gb: English (United Kingdom),
:us: English (United States),
:de: German  (Germany)

## :heavy_check_mark: Requirements
* Apache or Nginx
* PHP 8.1

## :hammer: Installation

### Git (recommended)
```
git clone -b stable https://github.com/grandeljay/wishthis.git .
```

Note: after pulling updates for a new version you might be prompted to update the database schema in the wishthis user interface (if necessary). Make sure you are logged in.

### Manual
Download the code using the [stable branch](https://github.com/grandeljay/wishthis/tree/stable) and upload it to your server.

Note: You will have to manually update wishthis by replacing all files with the changes from the `stable` branch.

## :trophy: Contributing

### As a tester
In the wishthis plattform, navigate to:
1. Account -> Profile
1. Preferences

And set your channel to "Release candidate". Make sure to give feedback!

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

#### Yarn
Use one of the following commands.

| Command        | Description               |
| ---------------| ------------------------- |
| `yarn install` | Install all dependencies. |

#### Theme changes
```
cd semantic
```

And then one of the following commands:
- `gulp build`
- `gulp watch`

For more information see: https://fomantic-ui.com/introduction/build-tools.html

#### Code style
| Language | Style  |
| -------- | ------ |
| PHP      | PSR-12 |

## :construction: Roadmap
| Item                                                | Status              |
| --------------------------------------------------- | ------------------- |
| Group wishes by store                               | Planned             |
| Option to show/notify when a wish was fulfilled     | Planned             |
| Price field for wishes                              | Planned             |
| Redirect to original target after login             | Planned             |
| Save / bookmark wishlists from other users          | Planned             |
| Temporary undo button after fulfilling a wish       | Planned             |
| Activity feed and friends                           | Under consideration |
| Browser extension to quickly create wishes from url | Under consideration |
| Bulk add wishes via link list                       | Under consideration |
| Combined wishes                                     | Under consideration |
| Folders / Subcategories for wishlists               | Under consideration |
| Synchronise Steam wishlist                          | Under consideration |
