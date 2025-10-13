![wishthis logo](/src/assets/img/logo-readme.svg?v=2 "wishthis logo")

# Make a wish

wishthis is a simple, intuitive and modern wishlist platform to create, manage and view your wishes for any kind of occasion ([demo](https://wishthis.online)). Currently, wishthis is available in **99** different locales!

> [!IMPORTANT]
> wishthis automatically adds an affiliate tracking parameter to all amazon.de links. This behaviour cannot currently be deactivated. It was added in an attempt to monetise wishthis without impacting the user experience and to keep me motivated to work on the project.
>
> I am very conflicted about this, as I believe software should be free (as in libre), yet I live in a capitalist economy, which forces me to pay or die. As mentioned on [my sponsors profile](https://github.com/sponsors/grandeljay), I am working full time and am not dependent on any donations to live, but I am struggling to justify the sheer amount of time and thought I have spent and am still spending on wishthis, as it requires regular maintenance.
>
> Please reach out in the Discussions, Discord or Matrix to let me tknow what your opionion is on the matter.

## :desktop_computer: Screenshots

| Home                                                 | Wishlists                                                           |
| ---------------------------------------------------- | ------------------------------------------------------------------- |
| ![Home](/src/assets/img/screenshots/home.png "Home") | ![Wishlists](/src/assets/img/screenshots/wishlists.png "Wishlists") |

## :family_man_man_boy: Join the conversation

[![Discord](https://img.shields.io/discord/935867122729496616?color=6435c9&label=Discord&logo=discord&logoColor=%23fff&style=for-the-badge)](https://discord.gg/WrUXnpNyza)
[![Matrix](https://img.shields.io/matrix/wishthis:matrix.org?color=6435c9&label=Matrix&logo=matrix&logoColor=%23fff&style=for-the-badge)](https://matrix.to/#/#wishthis:matrix.org)

## :heavy_check_mark: Requirements

-   Apache
-   PHP 8.1 - 8.3
    -   [intl](https://www.php.net/manual/en/book.intl.php)
    -   [mbstring](https://www.php.net/manual/en/mbstring.installation.php)
    -   [session](https://www.php.net/manual/en/book.session.php) (enabled by default)
-   MySQL/MariaDB
-   [MJML](https://mjml.io/api) api keys (not required and used for rendering emails. Make sure [sendmail](https://www.php.net/manual/en/mail.configuration.php) is configured properly.)

## :hammer: Installation

### Git (recommended)

```
git clone -b stable https://github.com/wishthis/wishthis.git .
```

Note: after pulling updates for a new version you might be prompted to update the database schema in the wishthis user interface (if necessary). Make sure you are logged in.

### Manual

Download the code using the [stable branch](https://github.com/wishthis/wishthis/tree/stable) and upload it to your server.

Note: You will have to manually update wishthis by replacing all files with the changes from the `stable` branch.

### Note

Make sure wishthis is setup via a domain directly and not running inside a sub-folder.

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

> [!IMPORTANT]
> If you're a front-end developer and/or you know your stuff when it comes to JavaScript (and jQuery), I'd really appreciate your help! For me, the biggest hurdle when adding new features is usually the front end.

To setup your development environment you currently have two possibilities:

1. Git

    Clone this repository and you're good to go!

1. Docker

    An official Docker image is also available: https://github.com/wishthis/docker. It's created and maintained by [Hiobi](https://github.com/Hiobi), thanks!

#### Updating

##### fomantic-ui

Components can be set in `/semantic.json`.

To update fomantic.ui run:

1. `npm upgrade` to update the source files
1. `cd /node_modules/fomantic-ui`
1. `npx gulp install` to apply the new source files to the project
1. `npm gulp build` to build all assets
