<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use ETS\DocumentStorage\Storage;
use ETS\DocumentStorage\Exception\DocumentNotFoundException;
use ETS\DocumentStorage\Exception\DocumentNotStoredException;

class Filesystem implements Storage
{
    /** @var string $storageDir */
    private $storageDir;

    /**
     * @param string $storageDir
     *
     * @throws \InvalidArgumentException If $storageDir is not a directory
     * @throws \InvalidArgumentException If $storageDir is not writable
     */
    public function __construct($storageDir)
    {
        if (!is_dir($storageDir)) {
            throw new \InvalidArgumentException(sprintf('[%s] is not a directory', $storageDir));
        }

        if (!is_writable($storageDir)) {
            throw new \InvalidArgumentException(sprintf('[%s] is not writable', $storageDir));
        }

        $this->storageDir = $storageDir;
    }

    /**
     * @inheritdoc
     */
    public function store($pathOrBody, $docName, $oldDocName = null)
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

    /**
     * @inheritdoc
     */
    public function retrieve($docName)
    {
        $docPath = $this->getDocPath($docName);

        if (false === $contents = file_get_contents($docPath)) {
            throw new DocumentNotFoundException(sprintf('Could not retrieve [%s]', $docPath));
        }

        return $contents;
    }

    /**
     * @inheritdoc
     */
    public function getUrl($docName)
    {
    }

    /**
     * @param  string $docName
     * @return string
     */
    private function getDocPath($docName)
    {
        return $this->storageDir.DIRECTORY_SEPARATOR.$docName;
    }
}
