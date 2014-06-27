<?php

namespace ETS\DocumentStorage\Client;

interface DocumentStorage
{
    /**
     * @param resource|string $pathOrBody
     * @param string          $docName  the name of the file once uploaded
     * @param string          $docKey
     *
     * @return string the document key
     *
     * @throws ETS\DocumentStorage\Exception\DocumentNotUploadedException If upload failed
     */
    public function upload($pathOrBody, $docName = null, $docKey = null);

    /**
     * @param string $docKey
     */
    public function download($docKey);

    /**
     * @param string $docKey
     *
     * @return string the link to download the document
     *
     * @throws ETS\DocumentStorage\Exception\DocumentNotFoundException If no document is found
     */
    public function getDownloadLink($docKey);
}
