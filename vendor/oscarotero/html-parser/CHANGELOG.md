# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [0.1.8] - 2023-11-29
### Fixed
- libxml internal buffer leak [#5]

## [0.1.7] - 2022-12-17
### Added
- Support for PHP 8.2 [#4].

### Changed
- Use GitHub Workflows for testing.

## [0.1.6] - 2020-11-19
### Added
- Added a second argument to `parse` and `parseFragment` to specify the encoding instead autodetect [#2] [#3].

## [0.1.5] - 2020-08-31
### Fixed
- Support for PHP 8 [#1].

## [0.1.4] - 2019-12-15
### Fixed
- Changed the way to handle different encodings detecting the <meta charset> and <meta http-equiv> elements

## [0.1.3] - 2019-07-20
### Fixed
- UTF-8 enconding issues

## [0.1.2] - 2019-05-25
### Fixed
- Improved encoding detection

## [0.1.1] - 2019-04-21
### Fixed
- `parseFragment` was returning a fragment containing only the first element

## [0.1.0] - 2019-04-21
First version

[#1]: https://github.com/oscarotero/html-parser/issues/1
[#2]: https://github.com/oscarotero/html-parser/issues/2
[#3]: https://github.com/oscarotero/html-parser/issues/3
[#4]: https://github.com/oscarotero/html-parser/issues/4
[#5]: https://github.com/oscarotero/html-parser/issues/5

[0.1.8]: https://github.com/oscarotero/html-parser/compare/v0.1.7...v0.1.8
[0.1.7]: https://github.com/oscarotero/html-parser/compare/v0.1.6...v0.1.7
[0.1.6]: https://github.com/oscarotero/html-parser/compare/v0.1.5...v0.1.6
[0.1.5]: https://github.com/oscarotero/html-parser/compare/v0.1.4...v0.1.5
[0.1.4]: https://github.com/oscarotero/html-parser/compare/v0.1.3...v0.1.4
[0.1.3]: https://github.com/oscarotero/html-parser/compare/v0.1.2...v0.1.3
[0.1.2]: https://github.com/oscarotero/html-parser/compare/v0.1.1...v0.1.2
[0.1.1]: https://github.com/oscarotero/html-parser/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/oscarotero/html-parser/releases/tag/v0.1.0
