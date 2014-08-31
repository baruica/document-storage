<?php

namespace spec\ETS\DocumentStorage\Client;

use PhpSpec\ObjectBehavior;

class S3Spec extends ObjectBehavior
{
    function let(\Aws\S3\S3Client $s3Client)
    {
        $this->beConstructedWith($s3Client, 'bucket');
    }

    function it_implements_the_DocumentStorageClient_interface()
    {
        $this->shouldImplement('ETS\DocumentStorage\Client\DocumentStorageClient');
    }
}
