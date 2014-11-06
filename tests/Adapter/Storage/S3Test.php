<?php

namespace ETS\DocumentStorage\Tests\Adapter\Storage;

use Aws\S3\S3Client;

use ETS\DocumentStorage\Adapter\Storage\S3;

class S3Test extends \PHPUnit_Framework_TestCase
{
    /** @var \Aws\S3\S3Client */
    protected static $client;

    /** @var string */
    protected static $bucket;

    /** @var string */
    protected static $folder;

    /** @var \ETS\DocumentStorage\Adapter\Storage\S3 */
    protected static $s3;

    private $docNamesToStore = array(
        'test-doc.txt',
    );

    public static function setUpBeforeClass()
    {
        $processUser = posix_getpwuid(posix_geteuid());

        if (!file_exists(sprintf('/home/%s/.aws/credentials', $processUser['name']))) {
            throw new \PHPUnit_Framework_SkippedTestSuiteError('No credentials file found in home directory, skipping S3 tests.');
        }

        $region = 'eu-west-1';

        self::$client = S3Client::factory(
            array(
                'profile' => 'test',
                'region'  => $region,
            )
        );

        self::$bucket = uniqid('document-storage-tests-', true);
        self::$folder = 'test folder';

        self::$client->createBucket(
            array(
                'Bucket'             => self::$bucket,
                'LocationConstraint' => $region,
            )
        );

        self::$client->waitUntilBucketExists(array('Bucket' => self::$bucket));

        self::$s3 = new S3(
            self::$client,
            self::$bucket,
            self::$folder
        );
    }

    public static function tearDownAfterClass()
    {
        self::$client->clearBucket(self::$bucket);

        self::$client->deleteBucket(array('Bucket' => self::$bucket));

        self::$client->waitUntilBucketNotExists(array('Bucket' => self::$bucket));
    }

    /**
     * @return array
     */
    public function provideStore()
    {
        $docsToStore = array();
        foreach ($this->docNamesToStore as $docName) {
            $docsToStore[] = array($docName, 'test body');
        }

        return $docsToStore;
    }

    /**
     * @dataProvider provideStore
     *
     * @param string $docName
     * @param string $body
     */
    public function testStore($docName, $body)
    {
        $docUrl = self::$s3->store($body, $docName);

        $this->assertStringEndsWith($docName, $docUrl);
    }

    /**
     * @expectedException \ETS\DocumentStorage\Exception\DocumentNotStoredException
     */
    public function testFailingStorageThrowsAnException()
    {
        self::$s3->store(
            (boolean) true, // invalid type for the pathOrBody
            'docName'
        );
    }

    /**
     * @return array
     */
    public function provideDocNames()
    {
        return array(
            $this->docNamesToStore,
        );
    }

    /**
     * @depends      testStore
     * @dataProvider provideDocNames
     *
     * @param string $docName
     */
    public function testGetUrl($docName)
    {
        $docUrl = self::$s3->getUrl($docName);

        $this->assertStringEndsWith($docName, $docUrl);
    }

    /**
     * @depends testStore
     * @expectedException \ETS\DocumentStorage\Exception\DocumentNotFoundException
     */
    public function testRetrieveIfDocDoesNotExist()
    {
        self::$s3->retrieve('non-existing-doc.txt');
    }
}
