<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use Aws\S3\S3Client;

use ETS\DocumentStorage\Storage;
use ETS\DocumentStorage\Exception\DocumentNotFoundException;
use ETS\DocumentStorage\Exception\DocumentNotStoredException;

class S3 implements Storage
{
    /** @var \Aws\S3\S3Client */
    private $s3Client;

    /** @var string */
    private $bucket;

    /** @var string */
    private $directory;

    /**
     * @param \Aws\S3\S3Client $s3Client
     * @param string           $bucket
     * @param string           $directory
     */
    public function __construct(S3Client $s3Client, $bucket, $directory = null)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
        $this->directory = $directory;
    }

    /**
     * @inheritdoc
     */
    public function store($pathOrBody, $docName, $oldDocName = null)
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
            array(
                'Bucket' => $this->bucket,
                'Key'    => $this->getKeyPath($docName),
            )
        );

        return $uploadResult['ObjectURL'];
    }

    /**
     * @inheritdoc
     */
    public function retrieve($docName)
    {
        $args = array(
            'Bucket' => $this->bucket,
            'Key'    => $this->getKeyPath($docName),
        );

        try {
            return (string) $this->s3Client->getObject($args)->get('Body');
        } catch (\Exception $e) {
            throw new DocumentNotFoundException(
                sprintf('Unable to retrieve document [%s] from bucket [%s]', $this->getKeyPath($docName), $this->bucket)
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getUrl($docName)
    {
        return $this->s3Client->getObjectUrl(
            $this->bucket,
            $this->getKeyPath($docName)
        );
    }

    /**
     * @param  string $docName
     *
     * @return string
     */
    private function getKeyPath($docName)
    {
        return (null === $this->directory)
               ? $docName
               : $this->directory.'/'.$docName;
    }
}
