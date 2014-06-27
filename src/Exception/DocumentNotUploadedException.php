<?php

namespace ETS\DocumentStorage\Exception;

use Aws\S3\Exception\S3Exception;

class DocumentNotUploadedException extends S3Exception
{}
