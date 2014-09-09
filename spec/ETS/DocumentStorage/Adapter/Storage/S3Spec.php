<?php

namespace spec\ETS\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;

class S3Spec extends ObjectBehavior
{
    function let(\Aws\S3\S3Client $s3Client)
    {
        $this->beConstructedWith($s3Client, 'bucket');
    }

    function it_implements_the_Storage_interface()
    {
        $this->shouldImplement('ETS\DocumentStorage\Storage');
    }
}
