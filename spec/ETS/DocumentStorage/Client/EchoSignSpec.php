<?php

namespace spec\ETS\DocumentStorage\Client;

use PhpSpec\ObjectBehavior;

class EchoSignSpec extends ObjectBehavior
{
    function let(\ETS\EchoSignBundle\Api\Client $echoSignClient, \ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection $recipients)
    {
        $this->beConstructedWith($echoSignClient, $recipients);
    }

    function it_implements_the_DocumentStorageClient_interface()
    {
        $this->shouldImplement('ETS\DocumentStorage\Client\DocumentStorageClient');
    }
}
