ETSDocumentStorage
==================

[![Build Status](https://api.travis-ci.org/ETSGlobal/ETSDocumentStorage.png)](https://travis-ci.org/ETSGlobal/ETSDocumentStorage)

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
[Resources/meta/LICENSE](https://github.com/ETSGlobal/ETSDocumentStorage/blob/master/Resources/meta/LICENSE)
