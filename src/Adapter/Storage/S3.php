<?php

namespace DocumentStorage\Adapter\Storage;

use Aws\S3\S3Client;

use DocumentStorage\Storage;
use DocumentStorage\Exception\DocumentNotFoundException;
use DocumentStorage\Exception\DocumentNotStoredException;

class S3 implements Storage
{
    /** @var \Aws\S3\S3Client */
    private $s3Client;

    /** @var string */
    private $bucket;

    /** @var string */
    private $directory;

    public function __construct(S3Client $s3Client, string $bucket, string $directory = null)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
        $this->directory = $directory;
    }

    public function store($pathOrBody, string $docName, string $oldDocName = null) : string
    {
        try {
            $uploadResult = $this->s3Client->upload(
                $this->bucket,
                $this->getKeyPath($docName),
                file_exists($pathOrBody) ? file_get_contents($pathOrBody) : $pathOrBody
            );
        } catch (\Exception $e) {
            throw new DocumentNotStoredException(
                sprintf('There was an error storing the document [%s]', $e->getMessage())
            );
        }

        // We can poll the object until it is accessible
        $this->s3Client->waitUntil(
            'ObjectExists',
            [
                'Bucket' => $this->bucket,
                'Key' => $this->getKeyPath($docName),
            ]
        );

        return $uploadResult['ObjectURL'];
    }

    public function retrieve(string $docName) : string
    {
        $args = [
            'Bucket' => $this->bucket,
            'Key' => $this->getKeyPath($docName),
        ];

        try {
            return (string) $this->s3Client->getObject($args)->get('Body');
        } catch (\Exception $e) {
            throw new DocumentNotFoundException(
                sprintf('Unable to retrieve document [%s] from bucket [%s]', $this->getKeyPath($docName), $this->bucket)
            );
        }
    }

    public function getUrl(string $docName) : string
    {
        return $this->s3Client->getObjectUrl(
            $this->bucket,
            $this->getKeyPath($docName)
        );
    }

    private function getKeyPath(string $docName) : string
    {
        return (null === $this->directory)
               ? $docName
               : $this->directory.DIRECTORY_SEPARATOR.$docName;
    }
}
