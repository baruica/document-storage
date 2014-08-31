<?php

namespace ETS\DocumentStorage\Client;

use ETS\DocumentStorage\Exception\DocumentNotFoundException;
use ETS\DocumentStorage\Exception\DocumentNotUploadedException;

class Filesystem implements DocumentStorageClient
{
    /**
     * @var string $storageDir
     */
    private $storageDir;

    /**
     * @param string $storageDir
     *
     * @throws \InvalidArgumentException If $storageDir is not a directory or not a directory we can create on the fly
     * @throws \InvalidArgumentException If $storageDir is not writable
     */
    public function __construct($storageDir)
    {
        if (!is_dir($storageDir)) {
            if (!mkdir($storageDir, 0755, true)) {
                throw new \InvalidArgumentException(sprintf('[%s] is not a directory, or cannot be created', $storageDir));
            }
        }

        if (!is_writable($storageDir)) {
            throw new \InvalidArgumentException(sprintf('[%s] is not writable', $storageDir));
        }

        $this->storageDir = $storageDir;
    }

    /**
     * @see DocumentStorage::upload
     */
    public function upload($pathOrBody, $docName = null, $oldDocKey = null)
    {
        $docPath = $this->storageDir.DIRECTORY_SEPARATOR.$docName;

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
    public function download($docKey)
    {
        $docPath = $this->storageDir.DIRECTORY_SEPARATOR.$docName;

        if (false === $contents = file_get_contents($docPath)) {
            throw new DocumentNotFoundException(sprintf('Could not download [%s]', $docPath));
        }

        return $contents;
    }

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docKey)
    {}
}
