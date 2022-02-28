MJML in PHP
===========

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

$renderer = new \Qferrer\Mjml\Renderer\ApiRenderer($apiId, $secretKey);

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

To find out which version of the library is used by the API, check the `mjml_version` key in the payload returned by the API:
```
{
    "html": "\n    <!doctype html>\n    <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\n      <head>\n        <title>\n          \n        </title>\n        <!--[if !mso]><!-- -->\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n        <!--<![endif]-->\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n        <style type=\"text/css\">\n          #outlook a { padding:0; }\n          body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }\n          table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }\n          img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }\n          p { display:block;margin:13px 0; }\n        </style>\n        <!--[if mso]>\n        <xml>\n        <o:OfficeDocumentSettings>\n          <o:AllowPNG/>\n          <o:PixelsPerInch>96</o:PixelsPerInch>\n        </o:OfficeDocumentSettings>\n        </xml>\n        <![endif]-->\n        <!--[if lte mso 11]>\n        <style type=\"text/css\">\n          .mj-outlook-group-fix { width:100% !important; }\n        </style>\n        <![endif]-->\n        \n      <!--[if !mso]><!-->\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\">\n        <style type=\"text/css\">\n          @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);\n        </style>\n      <!--<![endif]-->\n\n    \n        \n    <style type=\"text/css\">\n      @media only screen and (min-width:480px) {\n        .mj-column-per-100 { width:100% !important; max-width: 100%; }\n      }\n    </style>\n    \n  \n        <style type=\"text/css\">\n        \n        \n        </style>\n        \n        \n      </head>\n      <body>\n        \n        \n      <div\n         style=\"\"\n      >\n        \n      \n      <!--[if mso | IE]>\n      <table\n         align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\"\n      >\n        <tr>\n          <td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\">\n      <![endif]-->\n    \n      \n      <div  style=\"margin:0px auto;max-width:600px;\">\n        \n        <table\n           align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width:100%;\"\n        >\n          <tbody>\n            <tr>\n              <td\n                 style=\"direction:ltr;font-size:0px;padding:20px 0;text-align:center;\"\n              >\n                <!--[if mso | IE]>\n                  <table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n                \n        <tr>\n      \n            <td\n               class=\"\" style=\"vertical-align:top;width:600px;\"\n            >\n          <![endif]-->\n            \n      <div\n         class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;\"\n      >\n        \n      <table\n         border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align:top;\" width=\"100%\"\n      >\n        \n            <tr>\n              <td\n                 align=\"left\" style=\"font-size:0px;padding:10px 25px;word-break:break-word;\"\n              >\n                \n      <div\n         style=\"font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#000000;\"\n      >Hello World</div>\n    \n              </td>\n            </tr>\n          \n      </table>\n    \n      </div>\n    \n          <!--[if mso | IE]>\n            </td>\n          \n        </tr>\n      \n                  </table>\n                <![endif]-->\n              </td>\n            </tr>\n          </tbody>\n        </table>\n        \n      </div>\n    \n      \n      <!--[if mso | IE]>\n          </td>\n        </tr>\n      </table>\n      <![endif]-->\n    \n    \n      </div>\n    \n      </body>\n    </html>\n  ",
    "errors": [],
    "mjml": "<mjml><mj-body><mj-container><mj-section><mj-column><mj-text>Hello World</mj-text></mj-column></mj-section></mj-container></mj-body></mjml>",
    "mjml_version": "4.6.1"
}
```

More details in the API documentation: https://mjml.io/api/documentation/
