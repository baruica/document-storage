<?php

namespace DocumentStorage\Adapter\Storage;

use DocumentStorage\Storage;
use DocumentStorage\Exception\DocumentNotFoundException;
use DocumentStorage\Exception\DocumentNotStoredException;

class Filesystem implements Storage
{
    /** @var string */
    private $storageDir;

    /**
     * @throws \InvalidArgumentException If $storageDir is not a directory
     * @throws \InvalidArgumentException If $storageDir is not writable
     */
    public function __construct(string $storageDir)
    {
        if (!is_dir($storageDir)) {
            throw new \InvalidArgumentException(sprintf('[%s] is not a directory', $storageDir));
        }

        if (!is_writable($storageDir)) {
            throw new \InvalidArgumentException(sprintf('[%s] is not writable', $storageDir));
        }

        $this->storageDir = $storageDir;
    }

    public function store($pathOrBody, string $docName, string $oldDocName = '') : string
    {
        $docPath = $this->getDocPath($docName);

        $storage = file_exists($pathOrBody)
                 ? copy($pathOrBody, $docPath)
                 : file_put_contents($docPath, $pathOrBody);

        if (false === $storage) {
            throw new DocumentNotStoredException('There was an error storing the document [%s] to the filesystem.');
        }

        return $docPath;
    }

    public function retrieve(string $docName) : string
    {
        $docPath = $this->getDocPath($docName);

        if (false === file_exists($docPath) || false === $contents = file_get_contents($docPath)) {
            throw new DocumentNotFoundException(sprintf('Could not retrieve [%s]', $docPath));
        }

        return $contents;
    }

    public function getUrl(string $docName) : string
    {
    }

    private function getDocPath(string $docName) : string
    {
        return $this->storageDir.DIRECTORY_SEPARATOR.$docName;
    }
}
