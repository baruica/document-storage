<?php

namespace DocumentStorage;

interface Storage
{
    /**
     * @param string $pathOrBody
     * @param string $docName    the name of the document once stored
     * @param string $oldDocName
     *
     * @return string the document key
     *
     * @throws \DocumentStorage\Exception\DocumentNotStored If storage failed
     */
    public function store(string $pathOrBody, string $docName, string $oldDocName = '') : string;

    /**
     * @param string $docName
     *
     * @return string the body of the document
     *
     * @throws \DocumentStorage\Exception\DocumentNotFound If no document is found
     */
    public function retrieve(string $docName) : string;

    /**
     * @param string $docName
     *
     * @return string the document's publicly accessible URL
     */
    public function getUrl(string $docName) : string;
}
