<?php

namespace spec\ETS\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;

class EchoSignSpec extends ObjectBehavior
{
    function let(\ETS\EchoSignBundle\Api\Client $echoSignClient, \ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection $recipients)
    {
        $this->beConstructedWith($echoSignClient, $recipients);
    }

    function it_implements_the_Storage_interface()
    {
        $this->shouldImplement('ETS\DocumentStorage\Storage');
    }
}
