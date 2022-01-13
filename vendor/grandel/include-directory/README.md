# Include Directory

A simple PHP class to automatically include all files in a directory.

## Installation

```
composer require grandel/include-directory
```

## Usage

```php
require 'vendor/autoload.php';

$include = new Grandel\IncludeDirectory( __DIR__ . '/path/to/directory' );
```
