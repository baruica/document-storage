<?php

namespace spec\ETS\DocumentStorage\Client;

use PhpSpec\ObjectBehavior;

use ETS\DocumentStorage\Client\DocumentStorageClient;

class CompositeSpec extends ObjectBehavior
{
    function let(DocumentStorageClient $clientMock1, DocumentStorageClient $clientMock2)
    {
        $this->beConstructedWith(array(
            $clientMock1,
            $clientMock2
        ));
    }

    function it_implements_the_DocumentStorageClient_interface()
    {
        $this->beConstructedWith(array());
        $this->shouldImplement('ETS\DocumentStorage\Client\DocumentStorageClient');
    }

    function it_should_call_upload_on_its_clients_when_uploading(DocumentStorageClient $clientMock1, DocumentStorageClient $clientMock2)
    {
        $path = '/path';

        $clientMock1->upload($path, null, null)->shouldBeCalled();
        $clientMock2->upload($path, null, null)->shouldBeCalled();

        $this->upload($path);
    }

    function it_should_call_download_only_on_its_first_client_when_downloading(DocumentStorageClient $clientMock1, DocumentStorageClient $clientMock2)
    {
        $docKey = 'docKey';

        $clientMock1->download($docKey)->shouldBeCalled();
        $clientMock2->download($docKey)->shouldNotBeCalled();

        $this->download($docKey);
    }

    function it_should_call_getDownloadLink_only_on_its_first_client_when_getDownloadLink_is_called(DocumentStorageClient $clientMock1, DocumentStorageClient $clientMock2)
    {
        $docKey = 'docKey';

        $clientMock1->getDownloadLink($docKey)->shouldBeCalled();
        $clientMock2->getDownloadLink($docKey)->shouldNotBeCalled();

        $this->getDownloadLink($docKey);
    }
}
