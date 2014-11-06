<?php

namespace ETS\DocumentStorage\Exception;

use Aws\S3\Exception\S3Exception;

class DocumentNotStoredException extends S3Exception
{
}
