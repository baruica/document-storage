<?php

namespace ETS\DocumentStorage\Exception;

use Aws\S3\Exception\NoSuchKeyException;

class DocumentNotFoundException extends NoSuchKeyException
{
}
