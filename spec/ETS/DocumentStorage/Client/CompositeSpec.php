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
        $path    = '/path';
        $docName = 'docName';

        $clientMock1->upload($path, $docName, null)->shouldBeCalled();
        $clientMock2->upload($path, $docName, null)->shouldBeCalled();

        $this->upload($path, $docName);
    }

    function it_should_call_download_only_on_its_first_client_when_downloading(DocumentStorageClient $clientMock1, DocumentStorageClient $clientMock2)
    {
        $docName = 'docName';

        $clientMock1->download($docName)->shouldBeCalled();
        $clientMock2->download($docName)->shouldNotBeCalled();

        $this->download($docName);
    }

    function it_should_call_getDownloadLink_only_on_its_first_client_when_getDownloadLink_is_called(DocumentStorageClient $clientMock1, DocumentStorageClient $clientMock2)
    {
        $docName = 'docName';

        $clientMock1->getDownloadLink($docName)->shouldBeCalled();
        $clientMock2->getDownloadLink($docName)->shouldNotBeCalled();

        $this->getDownloadLink($docName);
    }
}
