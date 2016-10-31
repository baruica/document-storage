<?php declare(strict_types=1);

namespace tests\DocumentStorage\Adapter\Storage;

use PhpSpec\ObjectBehavior;
use DocumentStorage\Exception\DocumentNotFound;
use DocumentStorage\Storage;

class FilesystemSpec extends ObjectBehavior
{
    public function it_implements_the_Storage_interface()
    {
        $this->beConstructedWith('/tmp');
        $this->shouldImplement(Storage::class);
    }

    public function it_throws_an_exception_if_given_storage_dir_is_not_a_directory()
    {
        $storageDir = '/not/a/directory';

        $this
            ->shouldThrow(new \InvalidArgumentException(sprintf('[%s] is not a directory', $storageDir)))
            ->during('__construct', [$storageDir])
        ;
    }

    public function it_throws_an_exception_if_given_storage_dir_is_not_writable()
    {
        $storageDir = '/boot';

        $this
            ->shouldThrow(new \InvalidArgumentException(sprintf('[%s] is not writable', $storageDir)))
            ->during('__construct', [$storageDir])
        ;
    }

    public function it_throws_an_exception_if_it_cannot_retrieve_a_document()
    {
        $storageDir = '/tmp';
        $docName = 'non-exixtent-doc';

        $this->beConstructedWith($storageDir);

        $this
            ->shouldThrow(new DocumentNotFound(sprintf('Could not retrieve [%s]', $storageDir.DIRECTORY_SEPARATOR.$docName)))
            ->duringRetrieve($docName)
        ;
    }
}
