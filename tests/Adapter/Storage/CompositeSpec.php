<?php

namespace tests\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;

use DocumentStorage\Storage;

/**
 * @mixin \DocumentStorage\Adapter\Storage\Composite
 */
class CompositeSpec extends ObjectBehavior
{
    function let(Storage $storage1, Storage $storage2)
    {
        $this->beConstructedWith([
            $storage1,
            $storage2
        ]);
    }

    function it_implements_the_Storage_interface()
    {
        $this->shouldImplement(Storage::class);
    }

    function it_stores_by_calling_all_its_storages(Storage $storage1, Storage $storage2)
    {
        $path = '/path';
        $docName = 'docName';

        $storage1->store($path, $docName, null)->shouldBeCalled();
        $storage2->store($path, $docName, null)->shouldBeCalled();

        $this->store($path, $docName);
    }

    function it_calls_retrieve_only_on_its_first_storage_when_retrieving(Storage $storage1, Storage $storage2)
    {
        $docName = 'docName';

        $storage1->retrieve($docName)->shouldBeCalled();
        $storage2->retrieve($docName)->shouldNotBeCalled();

        $this->retrieve($docName);
    }

    function it_calls_getUrl_only_on_its_first_storage_when_getUrl_is_called(Storage $storage1, Storage $storage2)
    {
        $docName = 'docName';

        $storage1->getUrl($docName)->shouldBeCalled();
        $storage2->getUrl($docName)->shouldNotBeCalled();

        $this->getUrl($docName);
    }
}