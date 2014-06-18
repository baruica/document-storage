<?php

namespace ETS\DocumentStorage\Client;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

use ETS\DocumentStorage\Exception\DocumentNotFoundException;

class S3 implements DocumentStorage
{
    /**
     * @var Aws\S3\S3Client
     */
    private $s3Client;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @param Aws\S3\S3Client $s3Client
     * @param string          $bucket
     */
    public function __construct(S3Client $s3Client, $bucket)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
    }

    /**
     * @see DocumentStorage::upload
     */
    public function upload($pathOrBody, $docName = null, $docKey = null)
    {
        try {
            $result = $this->s3Client->upload(
                $this->bucket,
                $docName,
                $pathOrBody
            );
        } catch (S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }

        // We can poll the object until it is accessible
        $this->s3Client->waitUntil('ObjectExists', array(
            'Bucket' => $this->bucket,
            'Key'    => $docName
        ));

        return $result['ObjectURL'];
    }

    /**
     * @see DocumentStorage::download
     */
    public function download($docKey)
    {
        $result = $this->s3Client->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $docKey
        ));

        return $result->getUri();
    }

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docKey)
    {
        if (false === $this->s3Client->doesObjectExist($this->bucket, $docKey)) {
            throw new DocumentNotFoundException(sprintf('Document [%s] does not exist in bucket [%s]', $docKey, $this->bucket));
        }

        return $this->s3Client->getObjectUrl($this->bucket, $docKey);
    }
}
