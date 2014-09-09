<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use Aws\S3\S3Client;

use ETS\DocumentStorage\Storage;
use ETS\DocumentStorage\Exception\DocumentNotFoundException;
use ETS\DocumentStorage\Exception\DocumentNotUploadedException;

class S3 implements Storage
{
    /** @var Aws\S3\S3Client */
    private $s3Client;

    /** @var string */
    private $bucket;

    /** @var string */
    private $folder;

    /**
     * @param Aws\S3\S3Client $s3Client
     * @param string          $bucket
     */
    public function __construct(S3Client $s3Client, $bucket, $folder = null)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
        $this->folder = $folder;
    }

    /**
     * @see DocumentStorage::upload
     */
    public function upload($pathOrBody, $docName, $oldDocName = null)
    {
        try {
            $uploadResult = $this->s3Client->upload(
                $this->bucket,
                $this->getKeyPath($docName),
                file_exists($pathOrBody) ? file_get_contents($pathOrBody) : $pathOrBody
            );
        } catch (\Exception $e) {
            throw new DocumentNotUploadedException(
                sprintf('There was an error uploading the file [%s]', $e->getMessage())
            );
        }

        // We can poll the object until it is accessible
        $this->s3Client->waitUntil('ObjectExists', array(
            'Bucket' => $this->bucket,
            'Key'    => $this->getKeyPath($docName)
        ));

        return $uploadResult['ObjectURL'];
    }

    /**
     * @see DocumentStorage::download
     */
    public function download($docName)
    {
        try {
            $downloadResult = $this->s3Client->getObject(array(
                'Bucket' => $this->bucket,
                'Key'    => $this->getKeyPath($docName)
            ));
        } catch (\Exception $e) {
            throw new DocumentNotFoundException(
                sprintf('Document [%s] does not exist in bucket [%s]', $this->getKeyPath($docName), $this->bucket)
            );
        }

        return $downloadResult->getUri();
    }

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docName)
    {
        return $this->s3Client->getObjectUrl($this->bucket, $this->getKeyPath($docName));
    }

    /**
     * @param  string $docName
     * @return string
     */
    private function getKeyPath($docName)
    {
        return (null === $this->folder)
               ?                   $docName
               : $this->folder.'/'.$docName;
    }
}
