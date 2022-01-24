# HTML parser

Simple utility to parse html strings to DOMDocument.

```sh
composer require oscarotero/html-parser
```

## Usage

```php
use HtmlParser\Parser;

$html = '<html><head></head><body>Hello world</body></html>';

//Convert a string to a DOMDocument
$document = Parser::parse($html);

//Convert a string to a DOMDocumentFragment
$fragment = Parser::parseFragment('<p>Hello world</p>');

//Convert a DOMDocument or DOMDocumentFragment to a string
echo Parser::stringify($document);
echo Parser::stringify($fragment);
```
