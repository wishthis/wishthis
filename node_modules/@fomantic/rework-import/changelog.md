# 3.2.0 / 2025-02-08

* Remove re2 and url-regex(-safe) dependency by re-implementing a slightly changed protocol check from 2.0

# 3.1.0 / 2024-05-07

* Lock re2 and css-tool dependency versions to still support node 12

# 3.0.0 / 2022-10-28

* Switched from `css` to `@adobe/css-tools` which in turn drops deprecated sourceMap support
* Switched from `rework` to `@fomantic/rework` for the same reason

# 2.1.1 / 2022-10-28

* Replace `url-regex` by `url-regex-safe` to fix [CVE-2020-7661](https://nvd.nist.gov/vuln/detail/CVE-2020-7661)
* Update dependencies
* Fixed Tests on Windows

# 2.0.0 / 2014-11-27

* Use `url-regex` to check for @imports containing URLs
* Update `parse-import` to `2.0.0`
* Major improvements to code and tests

# 1.2.0 / 2014-07-04

* Update `css` to `2.0.0` ([ref](https://github.com/segmentio/myth/issues/86))

# 1.1.3 / 2014-06-27

* Fix a path stack issue

# 1.1.2 / 2014-06-26

* Drop graceful-fs to allow browser usage ([ref](https://github.com/segmentio/myth/issues/76))

# 1.1.1 / 2014-06-26

* Ignore protocol base URL

# 1.1.0 / 2014-06-25

* Better automatic path (using rework/css.parse source option)
* Better stack trace
* `transform` option (mainly to use [`css-whitespace`](https://github.com/reworkcss/css-whitespace))

# 1.0.0 / 2014-06-14

Merge with [rework-inline](https://www.npmjs.org/package/rework-inline) bring the following:

* Support media queries
* Add encoding option
* Use [find-file](https://www.npmjs.org/package/find-file) to lookup imports
* Automated tests
* Relative `@import`
* Better error reporting

# 0.0.3 / 2013-08-07

* Fix unused referenced package

# 0.0.2 / 2013-08-07

* Allow nested imports

# 0.0.1 / 2013-08-06

Initial release
