<?php

namespace spec\ETS\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;

use ETS\DocumentStorage\Storage;

class CompositeSpec extends ObjectBehavior
{
    function let(Storage $storage1, Storage $storage2)
    {
        $this->beConstructedWith(array(
            $storage1,
            $storage2
        ));
    }

    function it_implements_the_Storage_interface()
    {
        $this->beConstructedWith(array());
        $this->shouldImplement('ETS\DocumentStorage\Storage');
    }

    function it_should_call_upload_on_its_clients_when_uploading(Storage $storage1, Storage $storage2)
    {
        $path    = '/path';
        $docName = 'docName';

        $storage1->upload($path, $docName, null)->shouldBeCalled();
        $storage2->upload($path, $docName, null)->shouldBeCalled();

        $this->upload($path, $docName);
    }

    function it_should_call_download_only_on_its_first_client_when_downloading(Storage $storage1, Storage $storage2)
    {
        $docName = 'docName';

        $storage1->download($docName)->shouldBeCalled();
        $storage2->download($docName)->shouldNotBeCalled();

        $this->download($docName);
    }

    function it_should_call_getDownloadLink_only_on_its_first_client_when_getDownloadLink_is_called(Storage $storage1, Storage $storage2)
    {
        $docName = 'docName';

        $storage1->getDownloadLink($docName)->shouldBeCalled();
        $storage2->getDownloadLink($docName)->shouldNotBeCalled();

        $this->getDownloadLink($docName);
    }
}
