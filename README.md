# DocumentStorage library

Provides implementations to interact with various cloud storage services.

[![Build Status](https://img.shields.io/travis/baruica/document-storage.svg?style=flat-square)](https://travis-ci.org/baruica/document-storage)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/baruica/document-storage.svg?style=flat-square)](https://scrutinizer-ci.com/g/baruica/document-storage/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b12c51f-2338-40b3-95a6-fee5cee48993/mini.png)](https://insight.sensiolabs.com/projects/5b12c51f-2338-40b3-95a6-fee5cee48993)

[![Latest Version](https://img.shields.io/github/release/baruica/document-storage.svg?style=flat-square)](https://packagist.org/packages/mnapoli/invoker)
[![Total Downloads](https://poser.pugx.org/baruica/document-storage/downloads.svg)](https://packagist.org/packages/baruica/document-storage)

[![License](https://poser.pugx.org/baruica/document-storage/license.svg)](https://packagist.org/packages/baruica/document-storage)

## Installation

```bash
composer require baruica/document-storage
```

## Storage adapters

All storage adapters implement the ```DocumentStorage\Storage``` interface:
- ```DocumentStorage\Adapter\Storage\Composite```
- ```DocumentStorage\Adapter\Storage\Filesystem```
- ```DocumentStorage\Adapter\Storage\S3```

**To store a document:**
```php
$docUrl = $storage->store('body of a doc', 'docName');
```
The method returns the document's url.

**To get the document's url**
```php
$docUrl = $storage->getUrl('docName');
```
If the document doesn't exist, it will throw a ```DocumentStorage\Exception\DocumentNotFoundException```

## Code License

[LICENSE](https://github.com/baruica/document-storage/blob/master/LICENSE)
