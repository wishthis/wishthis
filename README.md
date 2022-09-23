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

## :earth_africa: Available in 28 different languages
| Language             |                  | Country        | Locale  |
| -------------------- | ---------------- | -------------- | ------- |
| Bulgarian            | :bulgaria:       | Bulgaria       | bg_BG   |
| Chinese (Simplified) |                  |                | zh-Hans |
| Czech                | :czech_republic: | Czech Republic | cs_CZ   |
| Danish               | :denmark:        | Denmark        | da_DK   |
| Dutch                | :netherlands:    | Netherlands    | nl_NL   |
| English              | :gb:             | United Kingdom | en_GB   |
| English              | :us:             | United States  | en_US   |
| Estonian             | :estonia:        | Estonia        | et_EE   |
| Finnish              | :finland:        | Finland        | fi_FI   |
| French               | :fr:             | France         | fr_FR   |
| German               | :de:             | Germany        | de_DE   |
| Greek                | :greece:         | Greece         | el_GR   |
| Hungarian            | :hungary:        | Hungary        | hu_HU   |
| Indonesian           | :indonesia:      | Indonesia      | id_ID   |
| Italian              | :it:             | Italy          | it_IT   |
| Japanese             | :jp:             | Japan          | ja_JP   |
| Latvian              | :latvia:         | Latvia         | lv_LV   |
| Lithuanian           | :lithuania:      | Lithuania      | lt_LT   |
| Polish               | :poland:         | Poland         | pl_PL   |
| Portuguese           | :brazil:         | Brazil         | pt_BR   |
| Portuguese           | :portugal:       | Portugal       | pt_PT   |
| Romanian             | :romania:        | Romania        | ro_RO   |
| Russian              | :ru:             | Russia         | ru_RU   |
| Slovak               | :slovakia:       | Slovakia       | sk_SK   |
| Slovenian            | :slovenia:       | Slovenia       | sl_SL   |
| Spanish              | :es:             | Spain          | es_ES   |
| Swedish              | :sweden:         | Sweden         | sv_SE   |
| Turkish              | :tr:             | Turkey         | tr_TR   |

## :heavy_check_mark: Requirements
* Apache or Nginx
* PHP 8.1
  * [intl](https://www.php.net/manual/en/book.intl.php)

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

#### Yarn
Use one of the following commands.

| Command        | Description               |
| ---------------| ------------------------- |
| `yarn install` | Install all dependencies. |

#### Updating fomantic-ui
To update fomantic-ui run the following commands
```
yarn upgrade
cd node_modules/fomantic-ui
npx gulp install
npx gulp build
```

or as a one-liner
```
yarn upgrade && cd node_modules/fomantic-ui && npx gulp install && npx gulp build && cd ../..
```

#### Theme changes
```
cd semantic
```

And then one of the following commands:
- `gulp build`
- `gulp watch`

For more information see: https://fomantic-ui.com/introduction/build-tools.html

#### Code style
| Language | Style                       |
| -------- | --------------------------- |
| PHP      | Custom (PSR-12 + WordPress) |

## :construction: Roadmap
| Item                                                | Status              |
| --------------------------------------------------- | ------------------- |
| Add "or similar" option to wishes                   | Planned             |
| Combined/separate (and/or) wishes                   | Planned             |
| Group wishes by store                               | Planned             |
| Option to show/notify when a wish was fulfilled     | Planned             |
| Redirect to original target after login             | Planned             |
| Request more wishes on shared wishlist              | Planned             |
| Activity feed and friends                           | Under consideration |
| Browser extension to quickly create wishes from url | Under consideration |
| Bulk add wishes via link list                       | Under consideration |
| Folders / Subcategories for wishlists               | Under consideration |
| Synchronise Steam wishlist                          | Under consideration |
