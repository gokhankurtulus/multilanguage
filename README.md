# MultiLanguage

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)
![Release](https://img.shields.io/github/v/release/gokhankurtulus/multilanguage.svg)

A simple PHP library for multi-language projects.

## Installation

You can install the library using Composer. Run the following command:

```bash
composer require gokhankurtulus/multilanguage
```

## Usage

You need to create a directory first and create the desired languages in this directory in JSON format.
Example JSON files are provided below.

Example Hierarchy

    project
       ├── Lang
       │    ├── de.json
       │    ├── en.json
       │    └── tr.json
       └── index.php

en.json

```json
{
  "Hello": "Hello #name# $lastname$",
  "Homepage": "Homepage"
}
```

tr.json

```json
{
  "Hello": "Selam #name# $lastname$",
  "Homepage": "Anasayfa"
}
```

de.json

```json
{
  "Hello": "Hallo #name# $lastname$",
  "Homepage": "Startseite"
}
```

index.php

```php
use MultiLanguage\MultiLanguage;

MultiLanguage::setDirectoryPath(__DIR__ . DIRECTORY_SEPARATOR . "Lang");
MultiLanguage::setAllowedLanguages(["en", "tr", "de"]);
MultiLanguage::setDefaultLanguage("en");
MultiLanguage::setCurrentLanguage("tr");

// Output will be "Homepage" because $lang parameter is set to 'en'.
// If $lang is null or empty tries to get current language
// if current is not defined then tries to get default language
// if both not defined and $lang is not given throws an LanguageException
echo MultiLanguage::translate('Homepage', 'en');

// Output will be "Anasayfa" because current language is 'tr'.
echo MultiLanguage::translate('Homepage');

// Output will be "Hallo John Doe".
// Specify unique keys in the language file then you can manipulate them.
// In this example, if your key is located many places in string it will change all of them.
echo MultiLanguage::translate('Hello', 'de', ['#name#' => 'John', '$lastname$' => 'Doe']);
```

### Public Methods

```php
MultiLanguage::translate(string  $text, ?string $lang = null, array $replacement = []);

MultiLanguage::getDirectoryPath();
MultiLanguage::setDirectoryPath(string $directoryPath, bool $force = false);

MultiLanguage::getAllowedLanguages();
MultiLanguage::setAllowedLanguages(array $languages);
MultiLanguage::isAllowedLanguage(string $lang);

MultiLanguage::getDefaultLanguage();
MultiLanguage::setDefaultLanguage(string $lang);

MultiLanguage::getCurrentLanguage();
MultiLanguage::setCurrentLanguage(string $lang);
```

## License

MultiLanguage is open-source software released under the [MIT License](LICENSE). Feel free to modify and use it in your
projects.

## Contributions

Contributions to MultiLanguage are welcome! If you find any issues or have suggestions for improvements, please create
an
issue or submit a pull request on
the [GitHub repository](https://github.com/gokhankurtulus/multilanguage).