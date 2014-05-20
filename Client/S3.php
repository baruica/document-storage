<?php

namespace ETS\DocumentStorage\Client;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3 implements DocumentStorage
{
    /**
     * @var Aws\S3\S3Client
     */
    private $s3;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @param Aws\S3\S3Client $s3
     * @param string          $bucket
     */
    public function __construct(S3Client $s3, $bucket)
    {
        $this->s3 = $s3;
        $this->bucket = $bucket;
    }

    public function upload($filePath, $docName = null, $docKey = null)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf('Cannot read file for upload [%s]', $filePath));
        }

        try {
            $result = $this->s3->upload(
                $this->bucket,
                $docKey,
                fopen($filePath, 'r')
            );
        } catch (S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }

        // We can poll the object until it is accessible
        $this->s3->waitUntil('ObjectExists', array(
            'Bucket' => $this->bucket,
            'Key'    => $docKey
        ));

        return $docKey;
    }

    public function download($docKey)
    {
        $result = $this->s3->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $docKey
        ));

        // The 'Body' value of the result is an EntityBody object
        echo get_class($result['Body']) . "\n";
        // > Guzzle\Http\EntityBody

        // The 'Body' value can be cast to a string
        echo $result['Body'] . "\n";
        // > Hello!
    }

    public function saveAs($docKey, $saveAs)
    {
        $result = $this->s3->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $docKey,
            'SaveAs' => $saveAs
        ));

        // Contains an EntityBody that wraps a file resource of $saveAs
        echo $result['Body']->getUri();
        // > $saveAs

        // Get the URL the object can be downloaded from
        echo $result['ObjectURL'];
    }

    public function getDownloadLink($docKey)
    {
        return $this->s3->getObjectUrl($this->bucket, $docKey);
    }
}
