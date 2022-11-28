MJML in PHP
===========

![PHPUnit](https://github.com/qferr/mjml-php/actions/workflows/php.yml/badge.svg)

A simple PHP library to render MJML to HTML.

There are two ways for integrating MJML in PHP:
* using the MJML API
* using the MJML library

### Installation

```shell script
composer require qferr/mjml-php
```

### Using MJML library

Install the MJML library:

```shell script
npm install mjml --save
```

If you want a specific version, use the following syntax: `npm install mjml@4.7.1 --save`

```php
<?php
require_once 'vendor/autoload.php';

$renderer = new \Qferrer\Mjml\Renderer\BinaryRenderer(__DIR__ . '/node_modules/.bin/mjml');

$html = $renderer->render('
    <mjml>
        <mj-body>
            <mj-section>
                <mj-column>
                    <mj-text>Hello world</mj-text>
                </mj-column>
            </mj-section>
        </mj-body>
    </mjml>
');
```

### Using MJML API

```php
<?php
require_once 'vendor/autoload.php';

$apiId = 'abcdef-1234-5678-ghijkl';
$secretKey = 'ghijkl-5678-1234-abcdef';

$api = new \Qferrer\Mjml\Http\CurlApi($apiId, $secretKey);
$renderer = new \Qferrer\Mjml\Renderer\ApiRenderer($api);

$html = $renderer->render('
    <mjml>
        <mj-body>
            <mj-section>
                <mj-column>
                    <mj-text>Hello world</mj-text>
                </mj-column>
            </mj-section>
        </mj-body>
    </mjml>
');
```

You can get the version of MJML used by the API to transpile:

```php
$api->getMjmlVersion();
```

More details in the API documentation: [https://mjml.io/api/documentation](https://mjml.io/api/documentation)
