![wishthis logo](/src/assets/img/logo-readme.svg?v=2 "wishthis logo")

# Make a wish

wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion ([demo](https://wishthis.online)). Currently, wishthis is available in **99** different locales!

## :desktop_computer: Screenshots

| Home                                                 | Wishlists                                                           |
| ---------------------------------------------------- | ------------------------------------------------------------------- |
| ![Home](/src/assets/img/screenshots/home.png "Home") | ![Wishlists](/src/assets/img/screenshots/wishlists.png "Wishlists") |

## :family_man_man_boy: Join the conversation

[![Discord](https://img.shields.io/discord/935867122729496616?color=6435c9&label=Discord&logo=discord&logoColor=%23fff&style=for-the-badge)](https://discord.gg/WrUXnpNyza)
[![Matrix](https://img.shields.io/matrix/wishthis:matrix.org?color=6435c9&label=Matrix&logo=matrix&logoColor=%23fff&style=for-the-badge)](https://matrix.to/#/#wishthis:matrix.org)

## :heavy_check_mark: Requirements

-   Apache or Nginx
-   PHP 8.1
    -   [intl](https://www.php.net/manual/en/book.intl.php)
-   MySQL/MariaDB
-   [MJML](https://mjml.io/api) api keys (not required and used for rendering emails. Make sure [sendmail](https://www.php.net/manual/en/mail.configuration.php) is configured properly.)

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

Localisation is currently done via Transifex.

https://www.transifex.com/wishthis/wishthis/

### As a sponsor

Time spent on wishthis is time not doing for-profit work. Of course there is no expectation but if you would still like to show your appreciation, you can here. It is very appreciated!

[![GitHub Sponsors](https://img.shields.io/github/sponsors/grandeljay?color=6435c9&logo=githubsponsors&logoColor=fff&style=for-the-badge)](https://github.com/sponsors/grandeljay)

### As a developer

To setup your development environment you currently have two possibilities:

1. Git

    Clone this repository and you're good to go!

1. Docker

    An unofficial Docker image is available on Docker Hub: [hiob/wishthis](https://hub.docker.com/r/hiob/wishthis), thanks [Hiobi](https://github.com/Hiobi)!
