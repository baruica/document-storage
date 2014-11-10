# ETS DocumentStorage library

[![Build Status](https://travis-ci.org/ETSGlobal/document-storage.png)](https://travis-ci.org/ETSGlobal/document-storage)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ETSGlobal/document-storage/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ETSGlobal/document-storage/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b12c51f-2338-40b3-95a6-fee5cee48993/mini.png)](https://insight.sensiolabs.com/projects/5b12c51f-2338-40b3-95a6-fee5cee48993)

[![Total Downloads](https://poser.pugx.org/ets/document-storage/downloads.svg)](https://packagist.org/packages/ets/document-storage)
[![Latest Stable Version](https://poser.pugx.org/ets/document-storage/v/stable.svg)](https://packagist.org/packages/ets/document-storage)
[![Latest Unstable Version](https://poser.pugx.org/ets/document-storage/v/unstable.svg)](https://packagist.org/packages/ets/document-storage)

[![License](https://poser.pugx.org/ets/document-storage/license.svg)](https://packagist.org/packages/ets/document-storage)

Provides implementations to interact with various cloud storage services.

## Installation

```bash
composer require ets/document-storage
```

## Storage adapters

All storage adapters implement the ```ETS\DocumentStorage\Storage``` interface:
- ```ETS\DocumentStorage\Adapter\Storage\Composite```
- ```ETS\DocumentStorage\Adapter\Storage\EchoSign```
- ```ETS\DocumentStorage\Adapter\Storage\Filesystem```
- ```ETS\DocumentStorage\Adapter\Storage\S3```

**To store a document:**
```php
$docUrl = $storage->store('body of a doc', 'docName');
```
The method returns the document's url.

**To get the document's url**
```php
$docUrl = $storage->getUrl('docName');
```
If the document doesn't exist, it will throw a ```ETS\DocumentStorage\Exception\DocumentNotFoundException```

## Code License

[LICENSE](https://github.com/ETSGlobal/document-storage/blob/master/LICENSE)
