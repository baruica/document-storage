<?php declare(strict_types=1);

namespace DocumentStorage\Adapter\Storage;

use DocumentStorage\Exception\DocumentNotFound;
use DocumentStorage\Storage;

class Composite implements Storage
{
    /** @var Storage[] */
    private $storages;

    public function __construct(Storage ...$storages)
    {
        $this->storages = $storages;
    }

    public function store(string $pathOrBody, string $targetDocName, string $oldDocName = '') : string
    {
        foreach ($this->storages as $storage) {
            $storage->store($pathOrBody, $targetDocName, $oldDocName);
        }

        return $targetDocName;
    }

    public function retrieve(string $docName) : string
    {
        if (empty($this->storages)) {
            throw new DocumentNotFound(sprintf('Could not retrieve [%s]', $docName));
        }

        return $this->storages[0]->retrieve($docName);
    }

    public function getUrl(string $docName) : string
    {
        if (empty($this->storages)) {
            throw new DocumentNotFound(sprintf('Could not retrieve [%s]', $docName));
        }

        return $this->storages[0]->getUrl($docName);
    }
}
