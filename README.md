# ETS DocumentStorage library

[![Latest Stable Version](https://poser.pugx.org/ets/document-storage/v/stable.svg)](https://packagist.org/packages/ets/document-storage)
[![Total Downloads](https://poser.pugx.org/ets/document-storage/downloads.svg)](https://packagist.org/packages/ets/document-storage)
[![Latest Unstable Version](https://poser.pugx.org/ets/document-storage/v/unstable.svg)](https://packagist.org/packages/ets/document-storage)
[![Build Status](https://travis-ci.org/ETSGlobal/ETSDocumentStorage.png)](https://travis-ci.org/ETSGlobal/ETSDocumentStorage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b12c51f-2338-40b3-95a6-fee5cee48993/mini.png)](https://insight.sensiolabs.com/projects/5b12c51f-2338-40b3-95a6-fee5cee48993)
[![License](https://poser.pugx.org/ets/document-storage/license.svg)](https://packagist.org/packages/ets/document-storage)

Provides implementations to interact with various document storage services

Installation
============
You can install it by using composer.
```
composer.phar require ets/document-storage
```
See the tags to know which version to use when it asks for a version.

Clients
=======
All clients implement ETS\DocumentStorage\Client\DocumentStorage:
- ETS\DocumentStorage\Client\EchoSign
- ETS\DocumentStorage\Client\S3

**To upload a document:**
```
$docUrl = $client->upload('body of a doc', 'docName');
```
The method returns the document's url.

**To get a document's download link**
```
$docUrl = $client->getDownloadLink('docName');
```
If the document doesn't exist, it will throw a ETS\DocumentStorage\Exception\DocumentNotFoundException.

Code License
============
[Resources/meta/LICENSE](https://github.com/ETSGlobal/document-storage/blob/master/Resources/meta/LICENSE)
