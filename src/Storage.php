<?php

namespace ETS\DocumentStorage;

interface Storage
{
    const CLASSNAME = __CLASS__;

    /**
     * @param resource|string $pathOrBody
     * @param string          $docName  the name of the document once stored
     * @param string          $oldDocName
     *
     * @return string the document key
     *
     * @throws \ETS\DocumentStorage\Exception\DocumentNotStoredException If storage failed
     */
    public function store($pathOrBody, $docName, $oldDocName = null);

    /**
     * @param string $docName
     *
     * @return string the body of the document
     *
     * @throws \ETS\DocumentStorage\Exception\DocumentNotFoundException If no document is found
     */
    public function retrieve($docName);

    /**
     * @param string $docName
     *
     * @return string the document's publicly accessible URL
     */
    public function getUrl($docName);
}
