<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [1.17.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.17.1...v1.17.2) (2024-05-19)

### Bug Fixes

* Wrong commits with non-latin description grouping ([ead95f](https://github.com/marcocesarato/php-conventional-changelog/commit/ead95f2ec5df3a8e1dbdc087b58e676cd7466fc7))


---

## [1.17.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.17.0...v1.17.1) (2024-03-09)

### Bug Fixes

* Symfony 7 compatibility command ([1926ac](https://github.com/marcocesarato/php-conventional-changelog/commit/1926ac42c365d1ab625155aca33b97a3f239ff19))


---

## [1.17.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.16.0...v1.17.0) (2023-03-26)

### Bug Fixes

* Cast `Scope` as string to use it as array key ([aff96f](https://github.com/marcocesarato/php-conventional-changelog/commit/aff96f70c05dec4191190eaf32951930f2b2570c))
* Check string offset exists before using it ([1fe715](https://github.com/marcocesarato/php-conventional-changelog/commit/1fe7152382ea5719a6a39067e4d8ce6e154f31f2))


---

## [1.16.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.6...v1.16.0) (2022-11-26)

### Features

* New option: Do not update CHANGELOG if no commits found. ([00a81e](https://github.com/marcocesarato/php-conventional-changelog/commit/00a81ee59f3f3e3f3b386f454d480a77538892ab))

### Bug Fixes

* Can parse header with carriage return and newline. ([ac4710](https://github.com/marcocesarato/php-conventional-changelog/commit/ac47108d2d1aed3e1e9fd8e7c5ea644a1216f8df))
* Retrieve last tag with extra [#48](https://github.com/marcocesarato/php-conventional-changelog/issues/48) ([454513](https://github.com/marcocesarato/php-conventional-changelog/commit/45451305801803aedc40cf01397440eea4524c0d))


---

## [1.15.6](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.5...v1.15.6) (2022-11-17)

### Bug Fixes

* Resolves problems with bumping alpha, beta and rc releases ([ad9eff](https://github.com/marcocesarato/php-conventional-changelog/commit/ad9efff67b1b83027865a2c1fa583d80370fb433))
* Resolves with extra releases ([e31c16](https://github.com/marcocesarato/php-conventional-changelog/commit/e31c16bfa180952fa8f10e271e49f4ffdfec8290))


---

## [1.15.5](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.4...v1.15.5) (2022-11-04)

### Bug Fixes

* Bump first time with extra version ([22b302](https://github.com/marcocesarato/php-conventional-changelog/commit/22b302aae1490eb78c018db9ed5148669d63c9d9))
* Extra tag bumping ([b6223f](https://github.com/marcocesarato/php-conventional-changelog/commit/b6223f47171af784e5c2a578db13d8afe49f1904))
* Semantic version regex compare ([a71a4b](https://github.com/marcocesarato/php-conventional-changelog/commit/a71a4bbaf46a72ffa27b5153a9da60554845ec31))


---

## [1.15.4](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.3...v1.15.4) (2022-10-27)

### Bug Fixes

* Restore last tag with refname method ([ef97fb](https://github.com/marcocesarato/php-conventional-changelog/commit/ef97fb098fb25b6f9c6c285fe2ffb32d8fc06211))


---

## [1.15.3](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.2...v1.15.3) (2022-10-27)

### Bug Fixes

* Prefix stripping and merged tags only [#47](https://github.com/marcocesarato/php-conventional-changelog/issues/47) ([60274c](https://github.com/marcocesarato/php-conventional-changelog/commit/60274c9bc220d0285ad535b14dfc630548a701cf))
* Semantic version prefix remove ([a821c1](https://github.com/marcocesarato/php-conventional-changelog/commit/a821c1366e9a2323d625af958b4196656ef01788))


---

## [1.15.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.1...v1.15.2) (2022-10-24)

### Bug Fixes

* Enable merged feature with new extra logics ([6a7d70](https://github.com/marcocesarato/php-conventional-changelog/commit/6a7d7090cc754ff73040db408c75bd02e2da2192))
* Extra release generation ([854599](https://github.com/marcocesarato/php-conventional-changelog/commit/854599bccf45b461e66bfd37a306f9c9a4db09c8))
* Force autobump on an extra release ([ce5d80](https://github.com/marcocesarato/php-conventional-changelog/commit/ce5d806de1afa01f87dfe73d35b45dead7c530a2))


---

## [1.15.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.15.0...v1.15.1) (2022-06-03)

### Bug Fixes

* Add blank line after changeLogVersionHeading [#39](https://github.com/marcocesarato/php-conventional-changelog/issues/39) ([9e4108](https://github.com/marcocesarato/php-conventional-changelog/commit/9e41083adb9d898c97c3e5d3d8561e9b84cae410))


---

## [1.15.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.14.1...v1.15.0) (2022-06-02)
### Features

* Add a getCurrentBranch method to Repository ([8b6266](https://github.com/marcocesarato/php-conventional-changelog/commit/8b62668eea440b64683f0af20a13065a7890be3f))


---

## [1.14.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.14.0...v1.14.1) (2022-05-24)
### Bug Fixes

* Breaking changes check [#37](https://github.com/marcocesarato/php-conventional-changelog/issues/37) ([476ff7](https://github.com/marcocesarato/php-conventional-changelog/commit/476ff7f8b2e6d3efffbfa4af152f4d0269491934))


---

## [1.14.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.13.0...v1.14.0) (2022-05-06)
### ⚠ BREAKING CHANGES

* Sort git tags by version instead of creation date, match prefix when listing tags ([042af9](https://github.com/marcocesarato/php-conventional-changelog/commit/042af9ec98d85519a9e91130e2fcbdd15b981565))

### Features

* Annotated tags ([e738b2](https://github.com/marcocesarato/php-conventional-changelog/commit/e738b29b22cbdaa1cd1490408cff3b1b4299a2f2))

### Bug Fixes

* Get last version with prefix ([2460c8](https://github.com/marcocesarato/php-conventional-changelog/commit/2460c81fab175e23260e449e4a4554a2e8d479c6))
* Issues with tag version fetch ([d64f35](https://github.com/marcocesarato/php-conventional-changelog/commit/d64f359450bc0b678c90eeb7aa926e2b39bff4b2))


---

## [1.13.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.12.3...v1.13.0) (2021-12-08)
### Features

* Added support for limiting commits to current branch. ([6aad51](https://github.com/marcocesarato/php-conventional-changelog/commit/6aad514354334b9a3041bfdbe0c81b98e8f118b1))

### Bug Fixes


##### Semver

* Bump minor version when breaking change before 1.0.0 ([5b13aa](https://github.com/marcocesarato/php-conventional-changelog/commit/5b13aa1408459be415f475a23e55983aaa297111))


---

## [1.12.3](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.12.2...v1.12.3) (2021-11-25)
### Features

* Allows own package bumpers ([e4aef9](https://github.com/marcocesarato/php-conventional-changelog/commit/e4aef903f14c981b8978ab1299eedc2f7e0888cb))


---

## [1.12.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.12.1...v1.12.2) (2021-10-22)
### Bug Fixes

* Repository url format detector [#22](https://github.com/marcocesarato/php-conventional-changelog/issues/22) ([63ff44](https://github.com/marcocesarato/php-conventional-changelog/commit/63ff443cbc53d2453ee217950d4664a15e9b3dd4))


---

## [1.12.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.12.0...v1.12.1) (2021-10-22)
### Features

* Allows issue id to be string ([30c20e](https://github.com/marcocesarato/php-conventional-changelog/commit/30c20e52369c723b53b054fa2199c8c52256d9a9))

### Bug Fixes

* Change repo parse remote url regex ([2dd6c8](https://github.com/marcocesarato/php-conventional-changelog/commit/2dd6c87faf67b809a8b37a763a2e33d5357141a4))
* Version separator markdown ([08092d](https://github.com/marcocesarato/php-conventional-changelog/commit/08092d21e8fa223de2bf4954b5d8f738109c36c1))


---

## [1.12.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.11.0...v1.12.0) (2021-08-24)
### Features

* Allows ability to hide version separator. ([e74c4d](https://github.com/marcocesarato/php-conventional-changelog/commit/e74c4d47130baa742397c7701fb27f5f6681d95d))

---

## [1.11.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.7...v1.11.0) (2021-07-28)
### Features

* Allows generated changelog to have configurable version headers ([f57e95](https://github.com/marcocesarato/php-conventional-changelog/commit/f57e953b7a8c3b96519238e0122bbb3cb949b9ba))

---

## [1.10.7](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.6...v1.10.7) (2021-05-15)


### Features

* Add option to render text instead of links in generated changelog ([5cb2e5](https://github.com/marcocesarato/php-conventional-changelog/commit/5cb2e5d2d8b9b445e38eede49dbc3a8036ba36a6))

---

## [1.10.6](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.5...v1.10.6) (2021-05-15)


### Bug Fixes

* Check if valid remote url [#14](https://github.com/marcocesarato/php-conventional-changelog/issues/14) ([7bc76c](https://github.com/marcocesarato/php-conventional-changelog/commit/7bc76cdaa11109efd43699d62ff3e71d1b445a15))
* Explicit compare ([78a084](https://github.com/marcocesarato/php-conventional-changelog/commit/78a084dca3f22e82614d2287ec350fe9cc05f539))

---

## [1.10.5](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.4...v1.10.5) (2021-05-15)


### Bug Fixes

* Output error on check requirements [#14](https://github.com/marcocesarato/php-conventional-changelog/issues/14) ([f020d9](https://github.com/marcocesarato/php-conventional-changelog/commit/f020d9e5a79fb355cd92785882eed8f325152d6a))
* Remove remote url repository requirement [#14](https://github.com/marcocesarato/php-conventional-changelog/issues/14) ([dacd7c](https://github.com/marcocesarato/php-conventional-changelog/commit/dacd7c3a82c2924047fcf4128e2d604f58c94978))

---

## [1.10.4](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.3...v1.10.4) (2021-05-14)


### Bug Fixes

* Add git remote repository url format check ([56aff6](https://github.com/marcocesarato/php-conventional-changelog/commit/56aff6304296c239aad8646e96d5452927857e84))
* Add requirements checks at startup [#14](https://github.com/marcocesarato/php-conventional-changelog/issues/14) ([b0b124](https://github.com/marcocesarato/php-conventional-changelog/commit/b0b1245cfca1bae60950d313fdb6d131c7cd5852))
* History version code ([eebfbe](https://github.com/marcocesarato/php-conventional-changelog/commit/eebfbea5e02a2e93d36eee3ba8ef357ecb269c79))
* Version code format and initialization [#14](https://github.com/marcocesarato/php-conventional-changelog/issues/14) ([b65bb6](https://github.com/marcocesarato/php-conventional-changelog/commit/b65bb669eaa33964b25b8fff97f0b1901d42b152))

##### Git

* Detect is inside work tree check remote url ([cb57a6](https://github.com/marcocesarato/php-conventional-changelog/commit/cb57a6e9c2d62e3c4009af6dd060acd6854b2bc2))

##### Semver

* Add get version code method ([54599f](https://github.com/marcocesarato/php-conventional-changelog/commit/54599fb8e91d6bc06bfc7881978f3a0fcde2602f))

##### Shell

* Add is enabled method ([edee7d](https://github.com/marcocesarato/php-conventional-changelog/commit/edee7de39313697b3c327a8729cc06c8d0774fb1))

---

## [1.10.3](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.2...v1.10.3) (2021-05-11)


### Bug Fixes


##### Composer Json

* Disable composer update on bump [#13](https://github.com/marcocesarato/php-conventional-changelog/issues/13) ([669262](https://github.com/marcocesarato/php-conventional-changelog/commit/669262ba6ceba78f82d6bb557c47564a90710716))

---

## [1.10.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.1...v1.10.2) (2021-04-16)


### Bug Fixes

* Skip tags and --not-tag are not working ([7f4d45](https://github.com/marcocesarato/php-conventional-changelog/commit/7f4d45886ad2723da11e4cb0073a7d29ead14e2d))

---

## [1.10.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.10.0...v1.10.1) (2021-04-15)


### Bug Fixes


##### Composer Json

* Composer update change path command ([18bb19](https://github.com/marcocesarato/php-conventional-changelog/commit/18bb19395d9c33905786ff1b1d834db369142c20))

---

## [1.10.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.10...v1.10.0) (2021-04-15)


### Features

* Add packages bumper [#11](https://github.com/marcocesarato/php-conventional-changelog/issues/11) ([0849aa](https://github.com/marcocesarato/php-conventional-changelog/commit/0849aad6d04e6640777a819391736edde56f00ba))

##### Composer Json

* Add composer update on save [#11](https://github.com/marcocesarato/php-conventional-changelog/issues/11) ([ea8fb7](https://github.com/marcocesarato/php-conventional-changelog/commit/ea8fb7eded13e6924388f044044a9898ec810163))

##### Config

* Add bump package setting [#11](https://github.com/marcocesarato/php-conventional-changelog/issues/11) ([343dd6](https://github.com/marcocesarato/php-conventional-changelog/commit/343dd6e47259c0190aa41cad5a42597df64a0d0d))
* Add package lock commit setting [#11](https://github.com/marcocesarato/php-conventional-changelog/issues/11) ([2fc824](https://github.com/marcocesarato/php-conventional-changelog/commit/2fc8247fa77792ac1a6f6805196f3f42605321ea))

### Bug Fixes

* Add git command exists check ([9ecd9e](https://github.com/marcocesarato/php-conventional-changelog/commit/9ecd9ebb4979352f93e071a4b74686fe10c3b060))

##### Bump

* Add lock files to commit [#11](https://github.com/marcocesarato/php-conventional-changelog/issues/11) ([4da206](https://github.com/marcocesarato/php-conventional-changelog/commit/4da2061af0fb9e8da24215ed174896d5d8f4eb04))
* Unescape slashes on json encode [#11](https://github.com/marcocesarato/php-conventional-changelog/issues/11) ([29a83e](https://github.com/marcocesarato/php-conventional-changelog/commit/29a83e243c8fd29df984dfe33253f2836c8f9435))

---

## [1.9.10](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.9...v1.9.10) (2021-04-13)


### Bug Fixes

* Replace command constants response for symfony 4 compatibility [#10](https://github.com/marcocesarato/php-conventional-changelog/issues/10) ([639aec](https://github.com/marcocesarato/php-conventional-changelog/commit/639aec65cdcb86a0d5cfe9715d8a6516594700e6))

---

## [1.9.9](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.8...v1.9.9) (2021-04-02)


### Bug Fixes

* Commit properties from protected to public for sorting ([d3b658](https://github.com/marcocesarato/php-conventional-changelog/commit/d3b65854ee00139e43577b03d3d2eb04dd489fd3))

---

## [1.9.8](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.7...v1.9.8) (2021-04-02)


### Bug Fixes

* Commit sorting on changelog [#9](https://github.com/marcocesarato/php-conventional-changelog/issues/9) ([0d402c](https://github.com/marcocesarato/php-conventional-changelog/commit/0d402cb2904e1aaff19d95616332462fdf519dec))

---

## [1.9.7](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.6...v1.9.7) (2021-03-27)


### Bug Fixes

* Array key exists on mixed value check ([4007ab](https://github.com/marcocesarato/php-conventional-changelog/commit/4007abe2b325ae7235c69cd4d4f66fb1f2d8d461))

---

## [1.9.6](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.5...v1.9.6) (2021-03-12)


### Bug Fixes

* Incorrect parsing of URLs [#7](https://github.com/marcocesarato/php-conventional-changelog/issues/7) ([24ca1c](https://github.com/marcocesarato/php-conventional-changelog/commit/24ca1c20b579625266ee8de656cb4616bb38fb85))

---

## [1.9.5](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.4...v1.9.5) (2021-03-02)


### Bug Fixes


##### Git

* Incorrect user type in Mention ([0c9fc5](https://github.com/marcocesarato/php-conventional-changelog/commit/0c9fc5b7f1a4ad610a1429d38550e7a80d8c82a6))

---

## [1.9.4](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.3...v1.9.4) (2021-02-28)


### Bug Fixes

* Remove tag prefix and suffix on history release header ([070113](https://github.com/marcocesarato/php-conventional-changelog/commit/070113ac76f2f6606bb2acd9a9c6f03d4fc8da07))

---

## [1.9.3](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.2...v1.9.3) (2021-02-22)


### Bug Fixes

* Add tag prefix and suffix on compare url tag [#5](https://github.com/marcocesarato/php-conventional-changelog/issues/5) ([db81e0](https://github.com/marcocesarato/php-conventional-changelog/commit/db81e0ec63a665d03170165b1623f72b4b70b08e))

---

## [1.9.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.1...v1.9.2) (2021-02-14)


### Features


##### Git

* Add closed attribute to references ([acb104](https://github.com/marcocesarato/php-conventional-changelog/commit/acb10474b5cb4f4002755ca7e55ab785c2750149))
* Add mention and reference class ([b22fa5](https://github.com/marcocesarato/php-conventional-changelog/commit/b22fa5c46b54ed7de6072445c90ad2a8a1b7408a))
* Commit classes auto compose when raw is empty ([0f7f9d](https://github.com/marcocesarato/php-conventional-changelog/commit/0f7f9d7d139c8ee0ccf53effa580735a0a40d508))

### Bug Fixes

* Using new reference class on changelog generation ([03e3a2](https://github.com/marcocesarato/php-conventional-changelog/commit/03e3a2bbaebd26db6fef3a7e699020c30d89511c))

##### Config

* Add isset check on setting ignore types [#4](https://github.com/marcocesarato/php-conventional-changelog/pull/4) ([22ab18](https://github.com/marcocesarato/php-conventional-changelog/commit/22ab180da24dec738f2dc1875590704ad473add9))
* Remove empty check of setting ignore types [#4](https://github.com/marcocesarato/php-conventional-changelog/pull/4) ([4b764d](https://github.com/marcocesarato/php-conventional-changelog/commit/4b764def749372df2270ccee7cbb4f9c8dc7ce01))

##### Git

* Add check empty commit on parse ([a26d6a](https://github.com/marcocesarato/php-conventional-changelog/commit/a26d6aeeecdd23e76773b5549b06800c4a240e09))
* Footer references detection ([6a40c1](https://github.com/marcocesarato/php-conventional-changelog/commit/6a40c1a0a3fa67f088450e1aef45e295371797eb))

---

## [1.9.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.9.0...v1.9.1) (2021-02-13)


### Features

* Add date format config ([05196a](https://github.com/marcocesarato/php-conventional-changelog/commit/05196ad943bffc5317df575d7a782ca04fb53421))

---

## [1.9.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.8.0...v1.9.0) (2021-02-13)


### Features

* Add pre and post run hooks and changes sorting ([a38ef1](https://github.com/marcocesarato/php-conventional-changelog/commit/a38ef1d6f74128322d031ef9404888a6e1706d38), [668d2e](https://github.com/marcocesarato/php-conventional-changelog/commit/668d2ea679f92c9f1022b95f93b83b614fd6d461))

##### Git

* Add delete tag method ([7712c2](https://github.com/marcocesarato/php-conventional-changelog/commit/7712c24494972365270aff896421f127003c2108))
* Add get last commit hash method ([c650b5](https://github.com/marcocesarato/php-conventional-changelog/commit/c650b5e48b3c79959af2d8b941818c2740cc4fae))
* Add no edit param to commit method ([bf4d1a](https://github.com/marcocesarato/php-conventional-changelog/commit/bf4d1a85e8303307dcd42c0318d6a33089bf49d7))

##### Semver

* Pattern version validation ([e19170](https://github.com/marcocesarato/php-conventional-changelog/commit/e191708265b8764823f397dbd28a8597edd2e0cd))

---

## [1.8.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.7.0...v1.8.0) (2021-02-12)


### Features

* Add pretty scope config ([f47356](https://github.com/marcocesarato/php-conventional-changelog/commit/f47356fac49edd01dab3f9cceb29481bab66758c))
* Add hidden configurations ([42c9af](https://github.com/marcocesarato/php-conventional-changelog/commit/42c9afe7aee84ab75ccc4ce9f7add83cf1922f51))
* Add user mentions ([214c75](https://github.com/marcocesarato/php-conventional-changelog/commit/214c75f84874ef4a92fd869230762c257d97c638))

### Bug Fixes

* Breaking changes indicated by a ! and ignore duplicated or empty message [#1](https://github.com/marcocesarato/php-conventional-changelog/issues/1) ([f3ebee](https://github.com/marcocesarato/php-conventional-changelog/commit/f3ebeee6bdac4e0fc1010ce3f7d3afd58bfda381))

---

## [1.7.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.6.2...v1.7.0) (2021-02-12)


### Features

* Urls and release commit formats ([7cb62a](https://github.com/marcocesarato/php-conventional-changelog/commit/7cb62acf85196bb576791bba6afd7ba55e2a3690))

##### Git

* Add parse remote url method ([81812c](https://github.com/marcocesarato/php-conventional-changelog/commit/81812c0838964198d577240ae12e79d059e18cc4))

---

## [1.6.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.6.1...v1.6.2) (2021-02-10)


### Bug Fixes

* Conventional commit wakeup class parse [#3](https://github.com/marcocesarato/php-conventional-changelog/issues/3) ([9eb3fb](https://github.com/marcocesarato/php-conventional-changelog/commit/9eb3fbee01f7e8717be67aaa5199bd51c7a17030))

---

## [1.6.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.6.0...v1.6.1) (2021-02-09)


### Bug Fixes

* Option commit all ([d84122](https://github.com/marcocesarato/php-conventional-changelog/commit/d84122283b244d213c2e5ee7329bce696bf85bc1))

##### Config

* Empty configurations from array [#1](https://github.com/marcocesarato/php-conventional-changelog/issues/1) ([481f05](https://github.com/marcocesarato/php-conventional-changelog/commit/481f051069f5c9a5ae2ec0f1375fa3ac8e81b9b4))

---

## [1.6.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.5.4...v1.6.0) (2021-01-28)


### Features

* Add tag prefix and suffix ([469f16](https://github.com/marcocesarato/php-conventional-changelog/commit/469f166f117478198d9fbdb55a4ea2b7f1d02ff3))
* Add commit all option ([323a5c](https://github.com/marcocesarato/php-conventional-changelog/commit/323a5c0c4bdb030938cea7b41056240cfbc4a9a6))
* Add skip verify, bump and tag ([1ae0e9](https://github.com/marcocesarato/php-conventional-changelog/commit/1ae0e964abf46ba2668c6562f59133654bea700d))

### Bug Fixes

* Option commit all ([d84122](https://github.com/marcocesarato/php-conventional-changelog/commit/d84122283b244d213c2e5ee7329bce696bf85bc1))

---

## [1.5.4](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.5.3...v1.5.4) (2021-01-24)


### Features

* Add commit parent class with raw metadata ([962e0f](https://github.com/marcocesarato/php-conventional-changelog/commit/962e0f7474ba34d7cfe01660025f272df9742eb8))

##### Config

* Add root setting ([2ad49c](https://github.com/marcocesarato/php-conventional-changelog/commit/2ad49c5614e5bcba17c95f142ea11e698d07a644))

### Bug Fixes


##### Config

* Use default settings if is not a valid config return value ([55fd29](https://github.com/marcocesarato/php-conventional-changelog/commit/55fd292f3a5d9068502b9ce54997d5015e03ad94))
* Check valid array ([849f43](https://github.com/marcocesarato/php-conventional-changelog/commit/849f43dcadef5361a64fa0ed5705a67850cef329))

---

## [1.5.3](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.5.2...v1.5.3) (2021-01-23)


### Bug Fixes

* Autobump on specified version ([9e35af](https://github.com/marcocesarato/php-conventional-changelog/commit/9e35af66941124a209bbc0c3ea54963c55a2f92c))

---

## [1.5.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.5.1...v1.5.2) (2021-01-22)


### Features

* Add check if inside a git work tree ([E3ad12](https://github.com/marcocesarato/php-conventional-changelog/commit/E3ad1287e1bfccfddf18bf52152dd1edba4eff2a))

##### Git

* Add inside work tree method ([081196](https://github.com/marcocesarato/php-conventional-changelog/commit/0811968babb8de519a3f249b4dcdb7da33a5ad99))

### Bug Fixes

* Auto bump new version code commit and tag ([Bf26ee](https://github.com/marcocesarato/php-conventional-changelog/commit/Bf26eee06484d2045c7f701f2e4b1f02fd430911))

##### Commit Parser

* Change return to string nullable on getters hash and raw ([5aa565](https://github.com/marcocesarato/php-conventional-changelog/commit/5aa565eb18090287e4033a6178acbe0959537d0b))

##### Git

* Get last tag name ([B265d0](https://github.com/marcocesarato/php-conventional-changelog/commit/B265d06b1f857d1f95db27a912fd74bd574c981a))
* Quotes on values exec return ([4e78c4](https://github.com/marcocesarato/php-conventional-changelog/commit/4e78c4ea8bd4e0f290d20284a7820d5392c04064))

---

## [1.5.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.5.0...v1.5.1) (2021-01-22)


### Bug Fixes

* Commits retrieve on ranges and remove additional whitespaces ([C3bffa](https://github.com/marcocesarato/php-conventional-changelog/commit/C3bffa74021727937c1f5b4a42efe534c820b943))

##### Config

* Possibile issues with empty data ([179c1f](https://github.com/marcocesarato/php-conventional-changelog/commit/179c1fa95fac3853b24a57d0c933d09e5500c328))

##### Git

* Get commits command compatibility and sorting get tags ([28d6ad](https://github.com/marcocesarato/php-conventional-changelog/commit/28d6ad76bb0ebfb1fe21ba70ec4e1b512ef6a3be))

---

## [1.5.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.4.3...v1.5.0) (2021-01-21)


### Features

* Split types and presets configuration  ([4239ec](https://github.com/marcocesarato/php-conventional-changelog/commit/4239ec0866ca0d2e21b8bf3394f2d888bf44c08a))
* Add auto versioning semver bump  ([82c883](https://github.com/marcocesarato/php-conventional-changelog/commit/82c8839300880d16c428abebca8f999f866baaeb))
* Add summary  ([C37116](https://github.com/marcocesarato/php-conventional-changelog/commit/C371169f3dde9bcdb16a9de6e339ae26d70a477a))
* Add to tag and from tag options  ([0b0c4b](https://github.com/marcocesarato/php-conventional-changelog/commit/0b0c4b06fcdb5e472914fa33f3175dbad3bac60e))

##### Command

* Add custom configuration file option  ([D65fc7](https://github.com/marcocesarato/php-conventional-changelog/commit/D65fc7472885116e891f383a6600d3df43b72e58))

##### Commit Parser

* Add getters and has scope method  ([83d99a](https://github.com/marcocesarato/php-conventional-changelog/commit/83d99aa44cba23bc0fe6b829c337cb9814a10bf9))
* Set default values on body and scope  ([B362bd](https://github.com/marcocesarato/php-conventional-changelog/commit/B362bd5167b2951333c12397c544233266676eda))

### Bug Fixes

* Breaking changes preserving the initial commit and customizable label  ([607f6e](https://github.com/marcocesarato/php-conventional-changelog/commit/607f6e09026eaee8b1b7e83fa4a7475d7a4f5694))
* Add hash check and rename parser to conventional  ([3e8996](https://github.com/marcocesarato/php-conventional-changelog/commit/3e899655c82737ca363995530ec300d4215c2a64))
* Customize changelog file path  ([1795ff](https://github.com/marcocesarato/php-conventional-changelog/commit/1795ffba53ea35108c05138054772e3bcde73b09))

##### Semver

* Bump major and minor  ([Fd7485](https://github.com/marcocesarato/php-conventional-changelog/commit/Fd7485f2b7ce77e32542b4431e37ea271d031878))

### Documentation


##### Readme

* Add usage gif image and improve usage section  ([Fbfdfe](https://github.com/marcocesarato/php-conventional-changelog/commit/Fbfdfe0f888aa5d36451c3e8bd589ddc7674b163))
* Add config option and adjust usage list example  ([Fbe9fd](https://github.com/marcocesarato/php-conventional-changelog/commit/Fbe9fdba9ee21ee915dcd18a04a3ff9240c90b1b))
* Merged notes on configuration section  ([9efedc](https://github.com/marcocesarato/php-conventional-changelog/commit/9efedcd91d1d84c022586e3d52ad88c02544547e))

### Chores

* Implement types configuration  ([4d90f1](https://github.com/marcocesarato/php-conventional-changelog/commit/4d90f1d2c1c7b87e6adfdb7b31de3cbc1df659c5))

##### Preset

* Change fixes description  ([Cbaa8f](https://github.com/marcocesarato/php-conventional-changelog/commit/Cbaa8fd627650303874f72e3206f8a1a6664064c))

---

## [1.4.4](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.4.3...v1.4.4) (2021-01-21)


### Features

* Split types and presets configuration  ([4239ec](https://github.com/marcocesarato/php-conventional-changelog/commit/4239ec0866ca0d2e21b8bf3394f2d888bf44c08a))
* Add summary  ([C37116](https://github.com/marcocesarato/php-conventional-changelog/commit/C371169f3dde9bcdb16a9de6e339ae26d70a477a))

##### Commit Parser

* Add getters and has scope method  ([83d99a](https://github.com/marcocesarato/php-conventional-changelog/commit/83d99aa44cba23bc0fe6b829c337cb9814a10bf9))
* Set default values on body and scope  ([B362bd](https://github.com/marcocesarato/php-conventional-changelog/commit/B362bd5167b2951333c12397c544233266676eda))

### Bug Fixes

* Breaking changes preserving the initial commit and customizable label  ([607f6e](https://github.com/marcocesarato/php-conventional-changelog/commit/607f6e09026eaee8b1b7e83fa4a7475d7a4f5694))
* Add hash check and rename parser to conventional  ([3e8996](https://github.com/marcocesarato/php-conventional-changelog/commit/3e899655c82737ca363995530ec300d4215c2a64))
* Customize changelog file path  ([1795ff](https://github.com/marcocesarato/php-conventional-changelog/commit/1795ffba53ea35108c05138054772e3bcde73b09))

##### Git

* Format option quotes  ([693860](https://github.com/marcocesarato/php-conventional-changelog/commit/693860b5ba9c5ddfd13919a48186aeaf22931f12))

### Chores

##### Preset

* Change fixes description  ([Cbaa8f](https://github.com/marcocesarato/php-conventional-changelog/commit/Cbaa8fd627650303874f72e3206f8a1a6664064c))

---

## [1.4.3](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.4.2...v1.4.3) (2021-01-19)


### Features

* Implement new commit parser and refactoring of helper classes  ([F2f2ec](https://github.com/marcocesarato/php-conventional-changelog/commit/F2f2ec80adfa787d453817faa64906e9dd988e44))
* Add new conventional commit parser  ([603f72](https://github.com/marcocesarato/php-conventional-changelog/commit/603f724dc759f4762c9b09489cb7620a98a10d67))
* Add breaking changes and issues references  ([Bda545](https://github.com/marcocesarato/php-conventional-changelog/commit/Bda5458e6c190a861417fb0239f86057c1d0c22f))

### Bug Fixes

* Semantic version extra part split  ([183116](https://github.com/marcocesarato/php-conventional-changelog/commit/18311683929859060af3c449a76fad41cb2baa52))
* Commit changes list generation  ([68b1ff](https://github.com/marcocesarato/php-conventional-changelog/commit/68b1ff9f424b47d0965702724e3cce5866b88892))
* Uppercase first char of scope on stringify  ([4eaebe](https://github.com/marcocesarato/php-conventional-changelog/commit/4eaebe96c84fa74aefa39d60fa1aeb3777316ce9))
* Move scope to string method to pretty string  ([D64421](https://github.com/marcocesarato/php-conventional-changelog/commit/D644210b973b1b16e3feeb140c3da881667888fa))

### Chores

* Change php requirements  ([5997ec](https://github.com/marcocesarato/php-conventional-changelog/commit/5997ecf5f02bbeedd1e31fe98cc5f6d8d743cb14))

---

## [1.4.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.4.1...v1.4.2) (2021-01-19)


### Bug Fixes

* Add chores to not notable types ([2c4d1c](https://github.com/marcocesarato/php-conventional-changelog/commit/2c4d1cdc23455f899922d1796bcd6fa343c6d589))

---

## [1.4.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.4.0...v1.4.1) (2021-01-18)


### Features

* Add semantic version parser ([7b5a9f](https://github.com/marcocesarato/php-conventional-changelog/commit/7b5a9fd99a4557bc7c167b99baa7ac3107d6f6c6))
* Add rc, alpha and beta release method ([f955e1](https://github.com/marcocesarato/php-conventional-changelog/commit/f955e18309fa8c304bb3fce0faaf23eb3e35eebe))

### Documentation


##### Readme

* Add new options on command list ([1ce4e3](https://github.com/marcocesarato/php-conventional-changelog/commit/1ce4e3b60c065e4ab7b4a0eaf272e427ecd58425))

---

## [1.4.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.3.0...v1.4.0) (2021-01-18)


### Features

* Add amend and no verify options ([625ed1](https://github.com/marcocesarato/php-conventional-changelog/commit/625ed1bfa6fc70716736be303b1f7a048b894756))
* Add not tag option and add errors on commit and tagging ([285ad0](https://github.com/marcocesarato/php-conventional-changelog/commit/285ad0a1bfe13b2460653a02ad40243184e7d80a))

### Bug Fixes


##### Config

* Get configs from project root or working dir ([ee9d65](https://github.com/marcocesarato/php-conventional-changelog/commit/ee9d654f3e340575cb69e3fa940083891c150716))
* Improve configuration and adjusted some settings ([cd6bdf](https://github.com/marcocesarato/php-conventional-changelog/commit/cd6bdf1de89c603afce1735487c4264c683473ab))

### Documentation


##### Readme

* Update description ([02c2c3](https://github.com/marcocesarato/php-conventional-changelog/commit/02c2c36c9e20aabbb708f6a12531300ea80e02b0))
* Fix configuration comment ([da733a](https://github.com/marcocesarato/php-conventional-changelog/commit/da733a985030a9d5ab99fd3683551fa44267f0ff))
* Add configuration notes and update example ([10c1f5](https://github.com/marcocesarato/php-conventional-changelog/commit/10c1f5a709961cb52a97009b63189dfaea8d1bf9))

---

## [1.3.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.2.0...v1.3.0) (2021-01-17)


### Features

* Add config file inclusion from working dir ([2cbb9f](https://github.com/marcocesarato/php-conventional-changelog/commit/2cbb9ff0bae2d25d4801845fec46d01d529fafe9))
* Implement configuration system ([28001b](https://github.com/marcocesarato/php-conventional-changelog/commit/28001b4fa9de6da256641a5cf377a72f281bbc2a))


### Documentation


##### Readme

* Add configuration section with an example ([bc6aa3](https://github.com/marcocesarato/php-conventional-changelog/commit/bc6aa3ea23d0cef82c150620d33fb6d50f4125c0))

---

## [1.2.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.1.0...v1.2.0) (2021-01-17)


### Features

* Add history option ([d53555](https://github.com/marcocesarato/php-conventional-changelog/commit/d53555442dbff0375db46649a306234309ba60ae))
* Add commit type exclusions options and add changelog file on commit ([055497](https://github.com/marcocesarato/php-conventional-changelog/commit/0554976a445d82db914cd256cc931f70b5923db6))

### Bug Fixes

* Add current release on history only with commit option flagged ([bac00d](https://github.com/marcocesarato/php-conventional-changelog/commit/bac00d478a9a93831af797080841c6991c6d660b))
* Git commit add files to the repository ([301184](https://github.com/marcocesarato/php-conventional-changelog/commit/301184dc9d17649b25db06f4e4d45ac316533c74))
* Auto commit success message ([5dec89](https://github.com/marcocesarato/php-conventional-changelog/commit/5dec89ce412e37476868076a0e550b41d122049d))
* Git commit release message ([b6f3e4](https://github.com/marcocesarato/php-conventional-changelog/commit/b6f3e461d348e21fbfde278385a8a7119defdf98))


### Documentation


##### Readme

* Add history option with examples ([33d51b](https://github.com/marcocesarato/php-conventional-changelog/commit/33d51b1ab1c7c9821554c407e69f6b95925755bc))
* Add no chores and no refactor options on command list ([106114](https://github.com/marcocesarato/php-conventional-changelog/commit/106114359633a230270139b867b0be18af8086e2))

### Chores

* Update history option description ([b7d180](https://github.com/marcocesarato/php-conventional-changelog/commit/b7d180fb0a506d79828b6d4ef88df7b33f4aab2d))

##### Composer

* Add scripts for changelog and release ([a5bd92](https://github.com/marcocesarato/php-conventional-changelog/commit/a5bd92f4baff375a1cafb4bc87190ff346930cb9))

---

## [v1.1.0](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.0.2...vv1.1.0) (2021-01-17)


### Features

* Add autoloader ([8e5667](https://github.com/marcocesarato/php-conventional-changelog/commit/8e5667550c2188e78a0d3ec8e5d388f413f0b813))
* Add information output about commit tag and file path ([7c7f4f](https://github.com/marcocesarato/php-conventional-changelog/commit/7c7f4f43995dd88a5b836b7c5f45b1144b7856fe))
* Implementing symfony console ([78e11b](https://github.com/marcocesarato/php-conventional-changelog/commit/78e11be242147cee1f7ab8ce526a9b969ef79b09))
* Add first release option ([143d6b](https://github.com/marcocesarato/php-conventional-changelog/commit/143d6b7828c8989e33ef23d22476ed14a7d4d935))

### Documentation

* Update description and add new instructions on readme ([3e1086](https://github.com/marcocesarato/php-conventional-changelog/commit/3e1086f844bd4af6609911333cbf97b0ed99cdee))

##### Readme

* Fix json composer scripts ([118cfd](https://github.com/marcocesarato/php-conventional-changelog/commit/118cfd275099a8534cd38176d5b6e1820fc88a4e))
* Remove duplicate line ([d88a00](https://github.com/marcocesarato/php-conventional-changelog/commit/d88a00b704f7d621f664facb5656e384b273f531))
* Clarify bump version processs ([ba9227](https://github.com/marcocesarato/php-conventional-changelog/commit/ba92277bdd60c2ff97a1ee26918f8bfca5527652))
* Add first release option, update commands list and last version code ([59e3bb](https://github.com/marcocesarato/php-conventional-changelog/commit/59e3bb92016dfcc1d553cd83450a0427007069a4))

### Chores

* Indicate default value of patch flag on helper list ([91df9c](https://github.com/marcocesarato/php-conventional-changelog/commit/91df9cc1600b9a2fb91be2ad2ba3eed9dafd3802))

##### Code Standard

* Change script for code standard fixing ([dba6bb](https://github.com/marcocesarato/php-conventional-changelog/commit/dba6bb1807ef72808cb879d913dad86eeee53c90))

---

## [v1.0.2](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.0.1...vv1.0.2) (2021-01-17)


### Documentation

* Specified date format to use on commands ([cdb712](https://github.com/marcocesarato/php-conventional-changelog/commit/cdb71211ce49c04e8789338b700839aaeb5303e1))

### Chores

##### Bin

* Move conventional changelog file to root ([f7a20c](https://github.com/marcocesarato/php-conventional-changelog/commit/f7a20c5ae16ea372b26f6402695d94a47b432866))

---

## [v1.0.1](https://github.com/marcocesarato/php-conventional-changelog/compare/v1.0.0...vv1.0.1) (2021-01-17)


### Documentation


##### Readme

* Add package description ([5a63a4](https://github.com/marcocesarato/php-conventional-changelog/commit/5a63a4e9e59347aa99753444e7156ca23b2b6058))
* Add info shields ([b96291](https://github.com/marcocesarato/php-conventional-changelog/commit/b9629156f0af4c3207e77739a52ccb5ddcf4f4da))

### Chores

* Report only fatal error ([0de3e0](https://github.com/marcocesarato/php-conventional-changelog/commit/0de3e0fc6d5419bd823bd73dd0bae27ecb7c3d33))

---

## [v1.0.0](https://github.com/marcocesarato/php-conventional-changelog/compare/021a49f43ef65ac7a594450374f1772eef1fd8b0...vv1.0.0) (2021-01-17)

### Description

- Generate changelogs and release notes from a project's commit messages and metadata using php composer and automate versioning with semver.org and conventionalcommits.org

---

