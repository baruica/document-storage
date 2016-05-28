<?php

namespace DocumentStorage\Adapter\Storage;

use DocumentStorage\Exception\DocumentNotFoundException;
use DocumentStorage\Storage;

class Composite implements Storage
{
    /** @var Storage[] */
    private $storages;

    public function __construct(array $storages)
    {
        $this->storages = $storages;
    }

    public function store($pathOrBody, string $docName, string $oldDocName = null) : string
    {
        foreach ($this->storages as $storage) {
            $storage->store($pathOrBody, $docName, $oldDocName);
        }

        return $docName;
    }

    public function retrieve(string $docName) : string
    {
        foreach ($this->storages as $storage) {
            return $storage->retrieve($docName);
        }

        throw new DocumentNotFoundException(sprintf('Could not retrieve [%s]', $docName));
    }

    public function getUrl(string $docName) : string
    {
        foreach ($this->storages as $storage) {
            return $storage->getUrl($docName);
        }

        throw new DocumentNotFoundException(sprintf('Could not retrieve [%s]', $docName));
    }
}
