<?php

namespace ETS\DocumentStorage\Tests\Client;

use Aws\S3\S3Client;

use ETS\DocumentStorage\Client\S3;

class S3Test extends \PHPUnit_Framework_TestCase
{
    protected static $client;
    protected static $bucket;
    protected static $s3;

    private $docsToUpload = array(
        'test-file.txt',
    );

    public static function setUpBeforeClass()
    {
        self::$client = S3Client::factory(array(
            'profile' => 'test',
            'region'  => 'eu-west-1',
        ));

        self::$bucket = uniqid('document-storage-tests-', true);

        self::$client->createBucket(array(
            'Bucket'             => self::$bucket,
            'LocationConstraint' => 'eu-west-1',
        ));

        // Wait until the bucket is created
        self::$client->waitUntilBucketExists(array('Bucket' => self::$bucket));

        self::$s3 = new S3(
            self::$client,
            self::$bucket
        );
    }

    public static function tearDownAfterClass()
    {
        self::$client->clearBucket(self::$bucket);

        self::$client->deleteBucket(array('Bucket' => self::$bucket));

        // Wait until the bucket is not accessible
        self::$client->waitUntilBucketNotExists(array('Bucket' => self::$bucket));
    }

    /**
     * @return array
     */
    public function provideUpload()
    {
        $uploads = array();
        foreach ($this->docsToUpload as $docName) {
            $uploads[] = array($docName, 'test body');
        }

        return $uploads;
    }

    /**
     * @dataProvider provideUpload
     */
    public function testUpload($docName, $body)
    {
        $docUrl = self::$s3->upload(
            $body,
            $docName
        );

        self::$client->waitUntil('ObjectExists', array(
            'Bucket' => self::$bucket,
            'Key'    => $docName,
        ));

        $this->assertStringEndsWith($docName, $docUrl);
    }

    /**
     * @expectedException ETS\DocumentStorage\Exception\DocumentNotUploadedException
     */
    public function testFailingUploadThrowsAnException()
    {
        self::$s3->upload(
            (boolean) true // invalid type for the pathOrBody
        );
    }

    /**
     * @return array
     */
    public function provideDocNames()
    {
        return array(
            $this->docsToUpload,
        );
    }

    /**
     * @depends testUpload
     * @dataProvider provideDocNames
     */
    public function testGetDownloadLink($docName)
    {
        $docUrl = self::$s3->getDownloadLink($docName);

        $this->assertStringEndsWith($docName, $docUrl);
    }

    /**
     * @expectedException ETS\DocumentStorage\Exception\DocumentNotFoundException
     */
    public function testGetDownloadLinkIfDocDoesNotExist()
    {
        self::$s3->getDownloadLink('non-existing-file.txt');
    }
}
