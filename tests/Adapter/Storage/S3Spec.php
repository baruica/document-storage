<?php

namespace tests\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;
use Aws\S3\S3Client;

use DocumentStorage\Storage;

/**
 * @mixin \DocumentStorage\Adapter\Storage\S3
 */
class S3Spec extends ObjectBehavior
{
    function let(S3Client $s3Client)
    {
        $this->beConstructedWith($s3Client, 'bucket');
    }

    function it_implements_the_Storage_interface()
    {
        $this->shouldImplement(Storage::class);
    }
}
