<?php

namespace ETS\DocumentStorage\Client;

use ETS\DocumentStorage\Exception\DocumentNotFoundException;
use ETS\DocumentStorage\Exception\DocumentNotUploadedException;

class Filesystem implements DocumentStorageClient
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
     * @see DocumentStorage::upload
     */
    public function upload($pathOrBody, $docName, $oldDocName = null)
    {
        $docPath = $this->getDocPath($docName);

        $upload = file_exists($pathOrBody)
                ? copy($pathOrBody, $docPath)
                : file_put_contents($docPath, $pathOrBody);

        if (false === $upload) {
            throw new DocumentNotUploadedException('There was an error saving the file [%s] to the filesystem.');
        }

        return $docPath;
    }

    /**
     * @see DocumentStorage::download
     */
    public function download($docName)
    {
        $docPath = $this->getDocPath($docName);

        if (false === $contents = @file_get_contents($docPath)) {
            throw new DocumentNotFoundException(sprintf('Could not download [%s]', $docPath));
        }

        return $contents;
    }

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docName)
    {}

    /**
     * @param  string $docName
     * @return string
     */
    private function getDocPath($docName)
    {
        return $this->storageDir.DIRECTORY_SEPARATOR.$docName;
    }
}
