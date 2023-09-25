# Changes in PHPUnit 10.0

All notable changes of the PHPUnit 10.0 release series are documented in this file using the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [10.0.0] - 2023-02-03

### Added

* [#4676](https://github.com/sebastianbergmann/phpunit/issues/4676): Event System for extending PHPUnit's test runner
* [#4502](https://github.com/sebastianbergmann/phpunit/issues/4502): Support PHP 8 attributes for adding metadata to test classes and test methods as well as tested code units
* [#4641](https://github.com/sebastianbergmann/phpunit/issues/4641): `assertStringEqualsStringIgnoringLineEndings()` and `assertStringContainsStringIgnoringLineEndings()`
* [#4650](https://github.com/sebastianbergmann/phpunit/issues/4650): Support dist file name `phpunit.dist.xml`
* [#4657](https://github.com/sebastianbergmann/phpunit/pull/4657): `--exclude-testsuite` option
* [#4818](https://github.com/sebastianbergmann/phpunit/pull/4818): `assertIsList()`
* [#4892](https://github.com/sebastianbergmann/phpunit/issues/4892): Make colors used in HTML code coverage report configurable
* [#4893](https://github.com/sebastianbergmann/phpunit/issues/4893): Make path to custom.css for HTML code coverage report configurable
* [#5097](https://github.com/sebastianbergmann/phpunit/issues/5097): Support for `enum` values in TestDox placeholder replacements
* `TestCase::createStubForIntersectionOfInterfaces()` and `TestCase::createMockForIntersectionOfInterfaces()`
* `#[ExcludeGlobalVariableFromBackup('variable')]` attribute for excluding a global variable from the backup/restore of global and super-global variables
* `#[ExcludeStaticPropertyFromBackup('className', 'propertyName')]` attribute for excluding a static property from the backup/restore of static properties in user-defined classes
* `--log-events-text <file>` option that controls streaming of event information (without event telemetry) in text format to a file
* `--log-events-verbose-text <file>` option that controls streaming of event information (with event telemetry) in text format to a file
* `--no-progress` option to disable test execution progress output
* `--no-results` option to disable test result output
* `--no-output` option to disable all output
* `--display-incomplete` option and `displayDetailsOnIncompleteTests` XML configuration attribute to control whether details on incomplete tests should be displayed
* `--display-skipped` option and `displayDetailsOnSkippedTests` XML configuration attribute to control whether details on skipped tests should be displayed
* `--display-deprecations` option and `displayDetailsOnTestsThatTriggerDeprecations` XML configuration attribute to control whether details on tests that trigger `E_DEPRECATED` or `E_USER_DEPRECATED` should be displayed
* `--display-errors` option and `displayDetailsOnTestsThatTriggerErrors` XML configuration attribute to control whether details on tests that trigger `E_ERROR` or `E_USER_ERROR` should be displayed
* `--display-notices` option and `displayDetailsOnTestsThatTriggerNotices` XML configuration attribute to control whether details on tests that trigger `E_STRING`, `E_NOTICE`, or `E_USER_NOTICE` should be displayed
* `--display-warnings` option and `displayDetailsOnTestsThatTriggerWarnings` XML configuration attribute to control whether details on tests that trigger `E_WARNING` or `E_USER_WARNING` should be displayed

### Changed

* [#3871](https://github.com/sebastianbergmann/phpunit/issues/3871): Declare return types for `InvocationStubber` methods
* [#3954](https://github.com/sebastianbergmann/phpunit/issues/3954): Disable global state preservation for process isolation by default
* [#4599](https://github.com/sebastianbergmann/phpunit/issues/4599): Unify cache configuration
* [#4603](https://github.com/sebastianbergmann/phpunit/issues/4603): Use "property" instead of "attribute" for configuring the backup of static fields
* [#4656](https://github.com/sebastianbergmann/phpunit/issues/4656): Prevent doubling of `__destruct()`
* Using a non-static method as a data provider is now deprecated 
* Using a non-public method as a data provider is now deprecated
* Declaring a data provider method to require an argument is now deprecated
* A test method now continues execution after test(ed) code triggered `E_(USER_)DEPRECATED`, `E_(USER_)NOTICE`, `E_STRICT`, or `E_(USER_)WARNING`
* PHPUnit no longer invokes a static method named `suite` on a class that is declared in a file that is passed as an argument to the CLI test runner
* PHPUnit no longer promotes variables that are global in the bootstrap script's scope to global variables in the test runner's scope (use `$GLOBALS['variable'] = 'value'` instead of `$variable = 'value'` in your bootstrap script)
* `PHPUnit\Framework\TestCase::$backupGlobals` can no longer be used to enable or disable the backup/restore of global and super-global variables for a test case class
* `PHPUnit\Framework\TestCase::$backupStaticAttributes` can no longer be used to enable or disable the backup/restore of static properties in user-defined classes for a test case class
* `@author` is no longer an alias for `@group`
* The JUnit XML logfile now has both `name` and `file` attributes on `<testcase>` elements for PHPT tests
* The JUnit XML logfile no longer has `<system-out>` elements that contain the output printed to `stdout` by a test
* The JUnit XML logfile now only reports test outcome (errored, failed, incomplete, skipped, or passed) and no longer test issues (considered risky, for instance)
* The `forceCoversAnnotation` attribute of the `<phpunit>` element of PHPUnit's XML configuration file has been renamed to `requireCoverageMetadata`
* The `beStrictAboutCoversAnnotation` attribute of the `<phpunit>` element of PHPUnit's XML configuration file has been renamed to `beStrictAboutCoverageMetadata`
* The public methods of `PHPUnit\Framework\Assert` and `PHPUnit\Framework\TestCase` are now `final`
* The `--testdox` CLI option no longer replaces the default progress output, but only the default result output
* The CLI test runner now only stops after a test errored when `--stop-on-error` or `--stop-on-defect` is used
* The CLI test runner now only stops after a test failed when `--stop-on-failure` or `--stop-on-defect` is used
* The CLI test runner now only stops after a test triggered a warning when `--stop-on-warning` or `--stop-on-defect` is used
* The CLI test runner now only stops after a test was marked as risky when `--stop-on-risky` or `--stop-on-defect` is used
* The CLI test runner now only stops after a test was skipped when `--stop-on-skipped` is used
* The CLI test runner now only stops after a test was marked as incomplete when `--stop-on-incomplete` is used

### Removed

* [#3389](https://github.com/sebastianbergmann/phpunit/issues/3389): Remove `PHPUnit\Framework\TestListener` and `PHPUnit\Framework\TestListenerDefaultImplementation`
* [#3631](https://github.com/sebastianbergmann/phpunit/issues/3631): Remove support for `"ClassName<*>"` as values for `@covers` and `@uses` annotations
* [#3769](https://github.com/sebastianbergmann/phpunit/issues/3769): Remove `MockBuilder::setMethods()` and `MockBuilder::setMethodsExcept()`
* [#3777](https://github.com/sebastianbergmann/phpunit/issues/3777): Remove `PHPUnit\Framework\Error\*` classes
* [#4063](https://github.com/sebastianbergmann/phpunit/issues/4063): Remove `assertNotIsReadable()`
* [#4066](https://github.com/sebastianbergmann/phpunit/issues/4066): Remove `assertNotIsWritable()`
* [#4069](https://github.com/sebastianbergmann/phpunit/issues/4069): Remove `assertDirectoryNotExists()`
* [#4072](https://github.com/sebastianbergmann/phpunit/issues/4072): Remove `assertDirectoryNotIsReadable()`
* [#4075](https://github.com/sebastianbergmann/phpunit/issues/4075): Remove `assertDirectoryNotIsWritable()`
* [#4078](https://github.com/sebastianbergmann/phpunit/issues/4078): Remove `assertFileNotExists()`
* [#4081](https://github.com/sebastianbergmann/phpunit/issues/4081): Remove `assertFileNotIsReadable()`
* [#4087](https://github.com/sebastianbergmann/phpunit/issues/4087): Remove `assertRegExp()`
* [#4090](https://github.com/sebastianbergmann/phpunit/issues/4090): Remove `assertNotRegExp()`
* [#4092](https://github.com/sebastianbergmann/phpunit/issues/4092): Remove `assertEqualXMLStructure()`
* [#4142](https://github.com/sebastianbergmann/phpunit/issues/4142): Remove Prophecy integration
* [#4227](https://github.com/sebastianbergmann/phpunit/issues/4227): Remove `--dump-xdebug-filter` and `--prepend`
* [#4272](https://github.com/sebastianbergmann/phpunit/issues/4272): Remove `PHPUnit\Util\Blacklist`
* [#4273](https://github.com/sebastianbergmann/phpunit/issues/4273): Remove `PHPUnit\Framework\TestCase::$backupGlobalsBlacklist`
* [#4274](https://github.com/sebastianbergmann/phpunit/issues/4274): Remove `PHPUnit\Framework\TestCase::$backupStaticAttributesBlacklist`
* [#4278](https://github.com/sebastianbergmann/phpunit/issues/4278): Remove `--whitelist` option
* [#4279](https://github.com/sebastianbergmann/phpunit/issues/4279): Remove support for old code coverage configuration
* [#4286](https://github.com/sebastianbergmann/phpunit/issues/4286): Remove support for old logging configuration
* [#4298](https://github.com/sebastianbergmann/phpunit/issues/4298): Remove `at()` matcher
* [#4397](https://github.com/sebastianbergmann/phpunit/issues/4397): Remove confusing parameter options for XML assertions
* [#4531](https://github.com/sebastianbergmann/phpunit/pull/4531): Remove `--loader` option as well as `testSuiteLoaderClass` and `testSuiteLoaderFile` XML configuration settings
* [#4536](https://github.com/sebastianbergmann/phpunit/issues/4536): Remove `assertFileNotIsWritable()`
* [#4596](https://github.com/sebastianbergmann/phpunit/issues/4595): Remove Test Hooks
* [#4564](https://github.com/sebastianbergmann/phpunit/issues/4564): Remove `withConsecutive()`
* [#4567](https://github.com/sebastianbergmann/phpunit/issues/4567): Remove support for generators in `assertCount()` and `Count` constraint
* [#4601](https://github.com/sebastianbergmann/phpunit/issues/4601): Remove assertions that operate on class/object properties
* Removed the `expectDeprecation()`, `expectDeprecationMessage()`, and `expectDeprecationMessageMatches()` methods
* Removed the `expectError()`, `expectErrorMessage()`, and `expectErrorMessageMatches()` methods
* Removed the `expectNotice()`, `expectNoticeMessage()`, and `expectNoticeMessageMatches()` methods
* Removed the `expectWarning()`, `expectWarningMessage()`, and `expectWarningMessageMatches()` methods
* Removed the `PHPUnit\Runner\TestSuiteLoader` interface
* Removed the `<listeners>` XML configuration element and its children
* Removed the `beStrictAboutResourceUsageDuringSmallTests` attribute on the `<phpunit>` XML configuration element and the `--disallow-resource-usage` option as well as the feature they used to control
* Removed the `beStrictAboutTodoAnnotatedTests` attribute on the `<phpunit>` XML configuration element and the `--disallow-todo-tests` option as well as the feature they used to control
* Removed the `convertDeprecationsToExceptions` attribute on the `<phpunit>` XML configuration element as well as the feature it used to control
* Removed the `convertErrorsToExceptions` attribute on the `<phpunit>` XML configuration element as well as the feature it used to control
* Removed the `convertNoticesToExceptions` attribute on the `<phpunit>` XML configuration element as well as the feature it used to control
* Removed the `convertWarningsToExceptions` attribute on the `<phpunit>` XML configuration element as well as the feature it used to control
* Removed the `noInteraction` attribute on the `<phpunit>` XML configuration element and `--no-interaction` option as well as the feature they used to control
* Removed the `processUncoveredFiles` attribute on the `<coverage>` XML configuration element
* Removed the `testdoxGroups` XML configuration element, the `--testdox-group` option, and the `--testdox-exclude-group` option as well as the feature they used to control
* Removed the `PHPUnit\Framework\TestCase::getMockClass()` method
* Removed the `PHPUnit\Framework\TestCase::$backupGlobalsExcludeList` property, use the `#[ExcludeGlobalVariableFromBackup('variable')]` attribute instead for excluding a global variable from the backup/restore of global and super-global variables
* Removed the `PHPUnit\Framework\TestCase::$backupStaticAttributesExcludeList` property, use the `#[ExcludeStaticPropertyFromBackup('className', 'propertyName')]` attribute instead for excluding a static property from the backup/restore of static properties in user-defined classes
* Removed the `PHPUnit\Framework\TestCase::$preserveGlobalState` property, use the `@preserveGlobalState enabled` annotation or the `#[PreserveGlobalState(true)]` attribute instead for enabling the preservation of global state when running tests in isolation
* Removed the `--repeat` option
* Removed the `--debug` option
* Removed the `--extensions` option
* Removed the `--printer` option
* Removed the `printerClass` and `printerFile` attributes on the `<phpunit>` XML configuration element
* Removed the `--testdox-xml` option and the `<testdoxXml>` XML configuration element as well as the feature they used to control
* Removed the `--verbose` option
* Removed the `verbose` attribute on the `<phpunit>` XML configuration element
* Removed the `<text>` XML configuration element (child of `<logging>`)
* The CLI test runner can no longer be extended through inheritance, the `PHPUnit\TextUI\Command` class has been removed
* PHP 7.3, PHP 7.4, and PHP 8.0 are no longer supported

[10.0.0]: https://github.com/sebastianbergmann/phpunit/compare/9.6...10.0.0
