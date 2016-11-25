<?php declare(strict_types=1);

namespace DocumentStorage;

interface Storage
{
    /**
     * @return string the document key
     *
     * @throws \DocumentStorage\Exception\DocumentNotStored If storage failed
     */
    public function store(string $pathOrBody, string $targetDocName, string $oldDocName = '') : string;

    /**
     * @return string the body of the document
     *
     * @throws \DocumentStorage\Exception\DocumentNotFound If no document is found
     */
    public function retrieve(string $docName) : string;

    /**
     * @return string the document's publicly accessible URL
     */
    public function getUrl(string $docName) : string;
}
