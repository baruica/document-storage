<?php

namespace spec\ETS\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;

use ETS\DocumentStorage\Exception\DocumentNotFoundException;

class FilesystemSpec extends ObjectBehavior
{
    function it_implements_the_Storage_interface()
    {
        $this->beConstructedWith('/tmp');
        $this->shouldImplement('ETS\DocumentStorage\Storage');
    }

    function it_throws_an_exception_if_given_storage_dir_is_not_a_directory()
    {
        $storageDir = '/not/a/directory';

        $this
            ->shouldThrow(new \InvalidArgumentException(sprintf('[%s] is not a directory', $storageDir)))
            ->during('__construct', array($storageDir))
        ;
    }

    function it_throws_an_exception_if_given_storage_dir_is_not_writable()
    {
        $storageDir = '/boot';

        $this
            ->shouldThrow(new \InvalidArgumentException(sprintf('[%s] is not writable', $storageDir)))
            ->during('__construct', array($storageDir))
        ;
    }

    function it_throws_an_exception_if_it_cannot_download_a_file()
    {
        $storageDir = '/tmp';
        $docName = 'non-exixtent-file';

        $this->beConstructedWith($storageDir);

        $this
            ->shouldThrow(new DocumentNotFoundException(sprintf('Could not download [%s]', $storageDir.DIRECTORY_SEPARATOR.$docName)))
            ->duringDownload($docName)
        ;
    }
}
